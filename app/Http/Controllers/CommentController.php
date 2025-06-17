<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function store(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate parent comment belongs to same article
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment->article_id !== $article->id) {
                return response()->json(['message' => 'Invalid parent comment'], 422);
            }
        }

        $comment = Comment::create([
            'content' => $request->content,
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'parent_id' => $request->parent_id,
        ]);

        $this->activityLogService->log(
            'comment_created',
            "Comment added to article '{$article->title}'",
            $request->user(),
            $comment
        );

        return response()->json($comment->load('user'), 201);
    }

    public function update(Request $request, Comment $comment)
    {
        // Only comment author can update
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment->update(['content' => $request->content]);

        $this->activityLogService->log(
            'comment_updated',
            "Comment updated",
            $request->user(),
            $comment
        );

        return response()->json($comment->load('user'));
    }

    public function destroy(Request $request, Comment $comment)
    {
        // Only comment author or admin can delete
        if ($comment->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->activityLogService->log(
            'comment_deleted',
            "Comment deleted",
            $request->user(),
            $comment
        );

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
