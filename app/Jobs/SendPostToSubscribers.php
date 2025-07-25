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
            //handle 1000 subscribers at a time
            Subscriber::select('id', 'email')->orderBy('id')->chunkById(1000, function ($subscribers) {
                foreach ($subscribers as $subscriber) {
                    $alreadySent = DB::table('post_subscribers')
                        ->where('post_id', $this->post->id)
                        ->where('subscriber_id', $subscriber->id)
                        ->exists();
                    // print_r($alreadySent); exit("die here");
                    if (!$alreadySent) {
                        Mail::to($subscriber->email)->queue(new NewPostMail($this->post)); // ✅ Queued mail
                        DB::table('post_subscribers')->insert([
                            'post_id' => $this->post->id,
                            'subscriber_id' => $subscriber->id
                        ]);
                    }
                }
            });
        } catch (\Throwable $th) {
            \Log::error('Error sending post to subscribers: ' . $th->getMessage());
        }
    }
}
