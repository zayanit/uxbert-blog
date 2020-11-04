<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'content', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function path()
    {
        return "/posts/{$this->id}";
    }
}
