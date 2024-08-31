<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags=Tag::with('posts')->get();
        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $request->validated();
        $tag = Tag::create($request->all());
        return response()->json($tag,201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $tag->load('posts');
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $request->validated();
        $tag->update($request->all());
        return response()->json($tag,200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json([
            'message'=>'tag deleted successfully'
        ],200);
    }
}
