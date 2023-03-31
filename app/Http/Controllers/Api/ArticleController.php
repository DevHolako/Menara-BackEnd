<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view articles')->only('index');
        $this->middleware('permission:view article')->only('show');
        $this->middleware('permission:store article')->only('store');
        $this->middleware('permission:update article')->only('update');
        $this->middleware('permission:soft-delete article')->only('destory');
        $this->middleware('permission:restore article')->only('restore');
        $this->middleware('permission:force-delete article')->only('forceDelete');
    }
    // Display a listing of the resource.
    public function index()
    {
        $articles = Article::all();
        if (!$articles) {
            return response()->json(["message" => "No articles were found"], 404);
        };
        return response()->json(['articles' => $articles]);
    }

    // Store a newly created resource in storage.
    public function store(Request $req)
    {

        $fileds = $req->validate([
            "categorie_code" => "required|exists:categories,categories_code",
            "designtion" => "required|string",
            "prix" => "required|numeric",
        ]);

        $article = Article::create($fileds);
        return response()->json(['message' => 'Article created successfully', 'article' => $article]);
    }

    // Display the specified resource.
    public function show(string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'article not found'], 404);
        }

        return response()->json(['article' => $article]);

    }

    // Update the specified resource in storage.
    public function update(Request $req, string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'article not found'], 404);
        }

        $fileds = $req->validate([
            "categorie_code" => "sometimes|exists:categories,categories_code",
            "designtion" => "sometimes|string",
            "prix" => "sometimes|numeric",
        ]);

        $article->update($fileds);

        return response()->json(['message' => 'article updated successfully', 'article' => $article]);

    }

    //  Soft Delete the specified resource from storage.
    public function destroy(string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'article not found'], 404);
        }

        $article->delete();

        return response()->json(['message' => 'article soft-deleted successfully'], 201);
    }

    // Restoure the specified resource from storage.
    public function restore(string $id)
    {
        $article = Article::onlyTrashed()->find($id);
        if (!$article) {
            return response()->json(['message' => 'article not found'], 404);
        }

        $article->restore();
        return response()->json(['message' => 'article restored successfully'], 201);

    }

    // Remove premently the specified resource from storage.
    public function forceDelete(string $id)
    {
        $article = Article::onlyTrashed()->find($id);
        if (!$article) {
            return response()->json(['message' => 'article not found'], 404);
        };

        $article->forceDelete();
        return response()->json(['message' => 'article premently deleted successfully'], 201);

    }

}
