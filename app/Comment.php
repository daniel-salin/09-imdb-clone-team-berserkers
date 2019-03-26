<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $fillable = [
        'headline', 'content', 'user_id',
    ];

    protected $attributes = [
        'tv_imdb_id' => null,
        'movie_imdb_id' => null,
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function review() 
    {
        return $this->belongsTo(Review::class);
    }
}