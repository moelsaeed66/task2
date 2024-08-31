<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($key)
    {
        if($key == 'posts')
        {
            $postsCount = Post::all()->count();
            return response()->json([
                'message' =>'the count of posts is : '.$postsCount
            ],200);
        }elseif ($key == 'users')
        {
            $usersCount = User::all()->count();
            return response()->json([
                'message' =>'the count of users is : '.$usersCount
            ],200);
        }elseif ($key == 'users-without-posts')
        {
            $usersWithoutPosts = User::doesntHave('posts')->get()->count();
            return response()->json([
                'message' =>'the count of users is : '.$usersWithoutPosts
            ],200);
        }else
        {
            return [
                'message' => 'this endpoint not fount',
            ];

        }
    }
}
