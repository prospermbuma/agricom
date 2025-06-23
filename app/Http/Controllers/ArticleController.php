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
        $user = $request->user();
        
        // Start with base query
        if ($user->isAdmin()) {
            // Admins can see all articles
            $query = Article::with('author');
        } elseif ($user->isVeo()) {
            // VEOs can see all articles (they can create and manage articles)
            $query = Article::with('author');
        } else {
            // Farmers can only see published articles
            $query = Article::with('author')->published();
        }

        // Filter by user's crops if farmer
        if ($user->isFarmerRole() && !empty($user->crop_ids)) {
            $query->where(function ($q) use ($user) {
                $q->forCrops($user->crop_ids)
                    ->orWhere('category', 'general');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by crop (cast to int if stored as int)
        if ($request->filled('crop')) {
            $query->whereJsonContains('target_crops', (int) $request->crop); 
        }

        // Search by title or content
        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $articles = $query->latest('created_at')->paginate(12);
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
        $article->load([
            'author.farmerProfile.region', 
            'author.farmerProfile.farmerCrops.crop',
            'author.region', 
            'comments.user', 
            'comments.replies.user'
        ]);
        $article->incrementViews();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->withProperties(['action' => 'article_viewed', 'ip_address' => request()->ip()])
            ->log('User viewed article: ' . $article->title);

        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $this->authorize('create', Article::class);

        // Debug: Log the request data
        \Log::info('Article creation attempt', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'validated_data' => $request->validated(),
            'files' => $request->allFiles(),
            'attachments' => $request->file('attachments')
        ]);

        $validated = $request->validated();
        $validated['author_id'] = Auth::id();
        
        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        
        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_published'] = $request->has('is_published');
        $validated['is_urgent'] = $request->has('is_urgent');

        // Convert target_crops to integers
        $validated['target_crops'] = collect($request->input('target_crops', []))
            ->map(fn($id) => (int) $id)
            ->toArray();

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

        try {
            $article = Article::create($validated);
            
            \Log::info('Article created successfully', ['article_id' => $article->id]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($article)
                ->withProperties(['action' => 'article_created', 'ip_address' => request()->ip()])
                ->log('Article "' . $validated['title'] . '" was created.');

            return redirect()->route('articles.show', $article)
                ->with('success', 'Article created successfully!');
        } catch (\Exception $e) {
            \Log::error('Article creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->withErrors(['error' => 'Failed to create article: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $validated = $request->validated();

        // Handle is_published field properly
        $validated['is_published'] = $request->boolean('is_published', false);

        // Convert target_crops to integers
        $validated['target_crops'] = collect($request->input('target_crops', []))
            ->map(fn($id) => (int) $id)
            ->toArray();

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
            ->withProperties(['action' => 'article_updated', 'ip_address' => request()->ip()])
            ->log('Article "' . $article->title . '" was updated.');

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
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
            ->withProperties(['action' => 'article_deleted', 'ip_address' => request()->ip()])
            ->log('Article "' . $article->title . '" was deleted.');

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
            ->withProperties(['action' => 'comment_added', 'ip_address' => request()->ip()])
            ->log('A comment was added to article "' . $article->title . '".');

        return back()->with('success', 'Comment added successfully!');
    }

    /**
     * Publish the specified article.
     */
    public function publish(Article $article)
    {
        $this->authorize('update', $article);

        $article->update([
            'is_published' => true,
            'published_at' => now()
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->withProperties(['action' => 'article_published', 'ip_address' => request()->ip()])
            ->log('Article "' . $article->title . '" was published.');

        return redirect()->route('articles.edit', $article->id)
            ->with('success', 'Article published successfully!');
    }

    /**
     * Unpublish the specified article.
     */
    public function unpublish(Article $article)
    {
        $this->authorize('update', $article);

        $article->update([
            'is_published' => false,
            'published_at' => null
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($article)
            ->withProperties(['action' => 'article_unpublished', 'ip_address' => request()->ip()])
            ->log('Article "' . $article->title . '" was unpublished.');

        return redirect()->route('articles.edit', $article->id)
            ->with('success', 'Article unpublished successfully!');
    }
}
