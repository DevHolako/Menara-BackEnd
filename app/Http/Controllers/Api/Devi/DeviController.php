<?php

namespace App\Http\Controllers\Api\Devi;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Devi\DeviResource;
use App\Models\Devi;
use Illuminate\Http\Request;

class DeviController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view devi')->only('index', 'show');
        $this->middleware('permission:store devi')->only('store');
        $this->middleware('permission:update devi')->only('update');
        $this->middleware('permission:soft-delete devi')->only('destory');
        $this->middleware('permission:restore devi')->only('restore');
        $this->middleware('permission:force-delete devi')->only('forceDelete');

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $devis = Devi::with('client', 'article')->get();
        $devi = DeviResource::collection(Devi::with('client', 'article')->get());
        return $devi;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'sometimes|date',
            'client_id' => 'required|exists:clients,id',
            'article' => 'required|array',
            'article.*.article_id' => 'required|exists:articles,id',
            'article.*.qty' => 'required',
            'article.*.prix' => 'required',
        ]);

        $devi = Devi::create($validatedData);
        $devi->article()->sync($request->input('article'));

        $devi->load('client', 'article');
        return new DeviResource($devi);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $devi = Devi::find($id);
        $devi->load('client', 'products');
        return new DeviResource($devi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $devi = Devi::find($id);
        $validatedData = $request->validate([
            'code' => 'required',
            'date' => 'required',
            'client_id' => 'required',
            'products' => 'required|array',
            'products.*.id' => 'required',
            'products.*.qty' => 'required',
            'products.*.price' => 'required',
        ]);

        $devi->update($validatedData);

        $devi->articles()->sync($request->input('products'));

        $devi->load('client', 'products');
        return new DeviResource($devi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $devi = Devi::find($id);
        $devi->delete();
        return response()->noContent();
    }
}
