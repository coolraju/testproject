<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email', 'website_id'];

    public function websites()
    {
        return $this->belongsToMany(Website::class, 'website_subscribers');
    }
}
