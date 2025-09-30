<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonumentTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'monument_id',
        'language',
        'title',
        'description',
        'history',
        'content',
        'location',
    ];

    /**
     * Get the monument that owns this translation.
     */
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}
