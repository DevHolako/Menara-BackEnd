<?php

namespace App\Http\Controllers\Api\Devi;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Devi\DeviResource;
use App\Models\Devi;
use Illuminate\Http\Request;

class DeviController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view devi')->only('index', 'show');
        $this->middleware('permission:store devi')->only('index', 'show', 'store');
        $this->middleware('permission:update devi')->only('index', 'show', 'update');
        $this->middleware('permission:delete devi')->only('index', 'show', 'destory', 'restore', 'forceDelete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return DeviResource::collection(Devi::with('client', 'article')->get());
        // return Devi::with('client', 'article')->get();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'sometimes|date',
            'client_id' => 'required|exists:clients,id',
            'articles' => 'required|array',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.designation' => 'required|exists:articles,designation',
            'articles.*.qty' => 'required',
            'articles.*.prix' => 'required',
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
        $devi->load('client', 'article');
        return new DeviResource($devi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $devi = Devi::find($id);
        $validatedData = $request->validate([
            'code' => 'sometimes',
            'date' => 'sometimes',
            'client_id' => 'sometimes',
            'articles' => 'sometimes|array',
            'articles.*.id' => 'sometimes',
            'articles.*.qty' => 'sometimes',
            'articles.*.price' => 'sometimes',
        ]);

        $devi->update($validatedData);

        $devi->articles()->sync($request->input('article'));

        $devi->load('client', 'article');
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
