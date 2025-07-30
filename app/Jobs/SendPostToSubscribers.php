<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NewPostMail;

class SendPostToSubscribers implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle(): void
    {
        try {
            $postId = $this->post->id;
    
            Subscriber::select('id', 'email', 'website_id')
                ->orderBy('id')
                ->chunkById(1000, function ($subscribers) use ($postId) {
                    $subscriberIds = $subscribers->pluck('id')->all();
    
                    // Get (subscriber_id => website_id) map
                    $subscriberWebsiteMap = $subscribers->pluck('website_id', 'id')->all();
    
                    // Query all sent combinations for these subscribers and this post
                    $alreadySent = DB::table('post_subscribers')
                        ->where('post_id', $postId)
                        ->whereIn('subscriber_id', $subscriberIds)
                        ->get(['subscriber_id', 'website_id']);
    
                    // Create a map of "subscriber_id|website_id" => true
                    $alreadySentMap = [];
                    foreach ($alreadySent as $record) {
                        $key = $record->subscriber_id . '|' . $record->website_id;
                        $alreadySentMap[$key] = true;
                    }
    
                    foreach ($subscribers as $subscriber) {
                        $key = $subscriber->id . '|' . $subscriber->website_id;
    
                        if (!isset($alreadySentMap[$key])) {
                            Mail::to($subscriber->email)->queue(new NewPostMail($this->post));
    
                            DB::table('post_subscribers')->insert([
                                'post_id' => $postId,
                                'subscriber_id' => $subscriber->id,
                                'website_id' => $subscriber->website_id,
                            ]);
                        }
                    }
                });
        } catch (\Throwable $th) {
            \Log::error('Error sending post to subscribers: ' . $th->getMessage());
        }
    }

}
