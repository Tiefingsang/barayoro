<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // Supprimez le constructeur avec middleware()
    // Les middleware se gèrent maintenant dans les routes

    /**
     * Afficher la liste des articles (vue liste) - PUBLIC
     */
    public function index(Request $request)
    {
        $query = BlogPost::where('status', 'published')
            ->with(['category', 'author']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);
        $categories = BlogCategory::where('is_active', true)->get();

        return view('blog.list', compact('posts', 'categories'));
    }

    /**
     * Afficher la vue en grille - PUBLIC
     */
    public function grid(Request $request)
    {
        $query = BlogPost::where('status', 'published')
            ->with(['category', 'author']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);
        $categories = BlogCategory::where('is_active', true)->get();

        return view('blog.grid', compact('posts', 'categories'));
    }

    /**
     * Afficher les détails d'un article - PUBLIC
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'author', 'comments.user'])
            ->firstOrFail();

        // Incrémenter le nombre de vues
        $post->increment('views');

        // Articles similaires
        $similarPosts = BlogPost::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->limit(3)
            ->get();

        return view('blog.details', compact('post', 'similarPosts'));
    }

    /**
     * Afficher le formulaire de création - ADMIN SEULEMENT
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $categories = BlogCategory::where('is_active', true)->get();
        return view('blog.create', compact('categories'));
    }

    /**
     * Enregistrer un nouvel article - ADMIN SEULEMENT
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $slug = Str::slug($request->title);
        
        // Vérifier l'unicité du slug
        $count = BlogPost::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image')->store('blog', 'public');
        }

        $post = BlogPost::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id ?? null,
            'author_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'tags' => $request->tags ? explode(',', $request->tags) : [],
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('blog.details', $post->slug)
            ->with('success', 'Article créé avec succès.');
    }

    /**
     * Afficher le formulaire d'édition - ADMIN SEULEMENT
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::where('is_active', true)->get();
        
        return view('blog.edit', compact('post', 'categories'));
    }

    /**
     * Mettre à jour un article - ADMIN SEULEMENT
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $post = BlogPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'tags' => $request->tags ? explode(',', $request->tags) : [],
            'status' => $request->status,
        ];

        if ($request->status === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $post->update($data);

        return redirect()->route('blog.details', $post->slug)
            ->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Supprimer un article - ADMIN SEULEMENT
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('blog.list')
            ->with('success', 'Article supprimé avec succès.');
    }
}