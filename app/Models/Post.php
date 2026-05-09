<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable=[
        'content',
        'image',
        'user_id',
        'likes_count',
        'comments_count',

    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

 public function getImageAttribute(?string $value): ?string
{
    if (!$value) {
        return null;
    }

    if (str_starts_with($value, 'http')) {
        return $value;
    }

    return asset('storage/' . $value);
}
}
