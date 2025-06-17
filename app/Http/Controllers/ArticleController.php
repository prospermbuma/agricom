<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $query = Article::published()->with('author');

        $query = Article::with(['author', 'comments'])
            ->published()
            ->orderBy('published_at', 'desc');

        // Filter by user's crops if farmer
        if ($request->user()->isFarmer() && !empty($request->user()->crops)) {
            $query->where(function ($q) use ($request) {
                $q->forCrops($request->user()->crops)
                    ->orWhere('category', 'general');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        // Filter by crop
        if ($request->has('crop') && $request->crop !== '') {
            $query->whereJsonContains('target_crops', $request->crop);
        }

        // Search by title or content
        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $articles = $query->latest('published_at')->paginate(12);
        $crops = Crop::all();
        $categories = [
            'pest_control' => 'Pest Control',
            'disease_management' => 'Disease Management',
            'farming_techniques' => 'Farming Techniques',
            'weather' => 'Weather',
            'market_prices' => 'Market Prices',
            'general' => 'General',
        ];

        return view('articles.index', compact('articles', 'crops', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['author', 'comments.user', 'comments.replies.user']);
        $article->incrementViews();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->log('Article viewed');

        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ArticleRequest $request)
    {
        // $this->authorize('create', Article::class);

        // Only VEOs can create articles
        if (!$request->user()->isVEO()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $crops = Crop::all();
        $categories = [
            'pest_control' => 'Pest Control',
            'disease_management' => 'Disease Management',
            'farming_techniques' => 'Farming Techniques',
            'weather' => 'Weather',
            'market_prices' => 'Market Prices',
            'general' => 'General',
        ];

        return view('articles.create', compact('crops', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        // $this->authorize('create', Article::class);

        // Only VEOs can create articles
        if (!$request->user()->isVEO()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validated();
        $validated['author_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published', false);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('articles/images', 'public');
        }

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('articles/attachments', 'public');
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->log('Article created');

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArticleRequest $request, Article $article)
    {
        // $this->authorize('update', $article);

        // Only author can update
        if ($article->author_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $crops = Crop::all();
        $categories = [
            'pest_control' => 'Pest Control',
            'disease_management' => 'Disease Management',
            'farming_techniques' => 'Farming Techniques',
            'weather' => 'Weather',
            'market_prices' => 'Market Prices',
            'general' => 'General',
        ];

        return view('articles.edit', compact('article', 'crops', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        // $this->authorize('update', $article);

        // Only author can update
        if ($article->author_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('articles/images', 'public');
        }

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            // Delete old attachments
            if ($article->attachments) {
                foreach ($article->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment);
                }
            }

            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('articles/attachments', 'public');
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing for the first time
        if ($validated['is_published'] && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->log('Article updated');

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleRequest $request, Article $article)
    {
        // $this->authorize('delete', $article);

        // Only author can delete
        if ($article->author_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete associated files
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        if ($article->attachments) {
            foreach ($article->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->log('Article deleted');

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully!');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function storeComment(CommentRequest $request, Article $article)
    {
        $validated = $request->validated();
        $validated['article_id'] = $article->id;
        $validated['user_id'] = Auth::id();

        $comment = Comment::create($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($comment)
            ->log('Comment added to article');

        return back()->with('success', 'Comment added successfully!');
    }
}
