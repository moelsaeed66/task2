<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('tags')->get();
//        dd($posts);
        return PostResource::collection($posts);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $validated = $request->validated();
        $data = $request->except('cover_image');
        $data['cover_image'] = $this->uploadImage($request);
        $post = Post::create($data);
        return response()->json($post,201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('tags');
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required','max:255'],
            'body' => ['required','string'],
            'cover_image' => ['image'],
            'pinned' => ['required']
        ]);
        $old_image = $request->cover_image;
        $data = $request->except('cover_image');
        $new_image = $this->uploadImage($request);
        if($new_image)
        {
            $data['cover_image'] = $new_image;
        }
        $post->update($request->all());
        return response()->json($post,200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post=$post->delete();
        return response()->json([
            'message'=>'post deleted successfully'
        ],200);
    }
    public function trashed()
    {
        $posts=Post::onlyTrashed()->get();
        return PostResource::collection($posts);
    }
    public function restore(Request $request ,string $id)
    {
        $post=Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return response()->json(
            [
                'message' => 'post restored successfully',
            ],200);

    }
    public function softDelete(string $id)
    {
        $post=Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();
        if($post->cover_image)
        {
            Storage::disk('public')->delete($post->cover_image);
        }
        return response()->json(
            [
                'message' => 'post deleted successfully',
            ],200);

    }
    protected function uploadImage(Request $request)
    {
        if(!$request->hasFile('cover_image'))
        {
            return;
        }
        $file=$request->file('cover_image');
        $path=$file->store('uploads','public');
        return $path;
    }
}
