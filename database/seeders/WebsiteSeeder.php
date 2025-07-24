<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Website;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Website::create(['name' => 'Hello World', 'url' => 'https://helloworld.com']);
        Website::create(['name' => 'Dummy Site','url' => 'http://demosite.com']);
    }
}
