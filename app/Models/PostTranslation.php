<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'language',
        'title',
        'description',
        'content',
    ];

    /**
     * Get the post that owns this translation.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
