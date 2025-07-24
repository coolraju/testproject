<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Subscriber;

class SubscriptionController extends Controller
{
    /**
     * Subscribe a user to a website's posts.
     */
    public function subscribe(Request $request, $website)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);
            //check website id exit
            $websiteid = Website::where('id',$website)->first();
            if(!$websiteid){
                return response()->json(['message' => 'Invalid Website']);
            }
            // print_r($validated); exit;
            // Check if the subscriber already exists
            $subscriber = Subscriber::where('email', $request->email)->where('website_id', $website)->first();
            // print_r($subscriber); exit;
            if ($subscriber) {
                return response()->json(['message' => 'Already subscribed.'], 200);
            }
            $subscriber = Subscriber::firstOrCreate(['email' => $validated['email'], 'website_id' => $website]);
            return response()->json(['message' => 'Subscribed successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Subscription failed.', 'error' => $th->getMessage()], 500);
        }
    }
}
