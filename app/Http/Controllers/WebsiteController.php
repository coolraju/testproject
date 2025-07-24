<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Website;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = Website::all();
        return response()->json($websites);
    }

    public function store(Request $request)
    {
        //validation
        $validated = $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
        ]);

        $website = Website::create($validated);
        return response()->json(['message' => 'Website created', 'data' => $website]);
    }
}