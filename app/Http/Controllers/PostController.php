<?php

namespace App\Http\Controllers;

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
        $posts=Post::orderBy('pinned','desc')->get();
        return view('posts.index',compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
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
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
//        $validated = $postRequest->validated();
        $request->validate([
            'title' => ['required','max:255'],
            'body' => ['required','string'],
            'cover_image' => ['image'],
            'pinned' => ['required']
        ]);
        $old_image=$post->image;
        $data=$request->except('image');
        $new_image=$this->uploadImage($request);
        if($new_image)
        {
            $data['image']=$new_image;
        }


        $post->update($data);
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post=$post->delete();
        return redirect()->route('posts.index');


    }
    public function trashed()
    {
        $posts=Post::onlyTrashed()->get();
        return view('posts.trashed',compact('posts'));
    }
    public function restore(PostRequest $request ,string $id)
    {
        $post=Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return redirect()->route('posts.index');
    }
    public function softDelete(string $id)
    {
        $post=Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();
        if($post->cover_image)
        {
            Storage::disk('public')->delete($post->cover_image);
        }
        return redirect()->route('posts.index');

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
