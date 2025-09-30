<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'monument_id',
        'title',
        'image_path',
        'description',
    ];

    /**
     * Get the monument that owns this gallery image.
     */
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }

    /**
     * Get the image URL - handles both local storage and Cloudinary URLs
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        // If it's already a full URL (Cloudinary), return as is
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        // If it's a local path, add storage prefix
        return asset('storage/' . $this->image_path);
    }
}
