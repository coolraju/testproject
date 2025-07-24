<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Jobs\SendPostToSubscribers;

class DispatchPostMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-post-mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::whereDoesntHave('subscribers')->get();
        // print_r($posts); exit;
        foreach ($posts as $post) {
            dispatch(new SendPostToSubscribers($post));
        }
    }
}
