<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function posts(){
        $posts = Post::with('category')->get();
        return response()->json($posts);
    }

    public function post($slug){
        $post = Post::with('category')->where("slug", $slug)->first();
        return response()->json($post);
    }

    public function categories(){
        $categories = Category::all();
        return response()->json($categories);
    }

    public function posts_catgory($slug){
        $category = Category::where("slug", $slug)->first();
        $posts = Post::with('category')->where("category_id", $category->id)->get();
        return response()->json($posts);
    }
}
