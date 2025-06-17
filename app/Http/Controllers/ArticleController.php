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
    public function index(Request $request)
    {
        $query = Article::published()->with('author');

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by crop
        if ($request->has('crop') && $request->crop !== '') {
            $query->whereJsonContains('target_crops', $request->crop);
        }

        // Search by title or content
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
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

    public function create()
    {
        $this->authorize('create', Article::class);
        
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

    public function store(ArticleRequest $request)
    {
        $this->authorize('create', Article::class);
        
        $validated = $request->validated();
        $validated['author_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);

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

    public function edit(Article $article)
    {
        $this->authorize('update', $article);
        
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

    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);
        
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

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        
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