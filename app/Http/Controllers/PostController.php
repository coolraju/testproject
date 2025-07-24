<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Post;
use App\Jobs\SendPostToSubscribers;

class PostController extends Controller
{
    public function store(Request $request, Website $website)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        
        $post = $website->posts()->create($validated);
        //send notification
        // SendPostToSubscribers::dispatch($post);
        return response()->json(['message' => 'Post created', 'data' => $post]);
    }
}
