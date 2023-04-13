<?php

namespace App\Http\Controllers\Api\eCommerce;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view categories')->only('index');
        $this->middleware('permission:view categorie')->only('show');
        $this->middleware('permission:store categorie')->only('store');
        $this->middleware('permission:update categorie')->only('update');
        $this->middleware('permission:soft-delete categorie')->only('destory');
        $this->middleware('permission:restore categorie')->only('restore');
        $this->middleware('permission:force-delete categorie')->only('forceDelete');
    }
    // Display a listing of the resource.
    public function index()
    {
        $categories = Categorie::all();
        if (!$categories) {
            return response()->json(["message" => "No Categories were found"], 404);
        };
        return response()->json($categories);
    }

    // Store a newly created resource in storage.
    public function store(Request $req)
    {

        $fileds = $req->validate([
            "intitule" => "required|string",
        ]);

        $categorie = Categorie::create($fileds);
        return response()->json(['message' => 'Categorie created successfully', 'Categorie' => $categorie]);
    }

    // Display the specified resource.
    public function show(string $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }

        return response()->json($categorie);

    }

    // Update the specified resource in storage.
    public function update(Request $req, string $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }

        $fileds = $req->validate([
            "intitule" => "required|string|unique:categories,intitule",
        ]);

        $categorie->update($fileds);

        return response()->json(['message' => 'Categorie updated successfully', 'Categorie' => $categorie]);

    }

    //  Soft Delete the specified resource from storage.
    public function destroy(string $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }

        $categorie->delete();

        return response()->json(['message' => 'Categorie soft-deleted successfully'], 201);
    }

    // Restoure the specified resource from storage.
    public function restore(string $id)
    {
        $categorie = Categorie::onlyTrashed()->find($id);
        if (!$categorie) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }

        $categorie->restore();
        return response()->json(['message' => 'Categorie restored successfully'], 201);

    }

    // Remove premently the specified resource from storage.
    public function forceDelete(string $id)
    {
        $categorie = Categorie::onlyTrashed()->find($id);
        if (!$categorie) {
            return response()->json(['message' => 'Categorie not found'], 404);
        };

        $categorie->forceDelete();
        return response()->json(['message' => 'Categorie premently deleted successfully'], 201);

    }

}
