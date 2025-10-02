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
        'category',
    ];

    protected $appends = ['image_url', 'thumbnail_url', 'blur_hash'];

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

    /**
     * Get optimized thumbnail URL (400x300) for gallery grid
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        // If it's a Cloudinary URL, add transformation
        if (filter_var($this->image_path, FILTER_VALIDATE_URL) && str_contains($this->image_path, 'cloudinary')) {
            // Insert transformation parameters before /upload/
            return str_replace('/upload/', '/upload/w_400,h_300,c_fill,q_auto,f_auto/', $this->image_path);
        }

        // For local storage, return original (you can add image processing later)
        return $this->image_url;
    }

    /**
     * Get tiny blur placeholder (20x15) for progressive loading
     */
    public function getBlurHashAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        // If it's a Cloudinary URL, create tiny blur placeholder
        if (filter_var($this->image_path, FILTER_VALIDATE_URL) && str_contains($this->image_path, 'cloudinary')) {
            return str_replace('/upload/', '/upload/w_20,h_15,c_fill,q_auto,f_auto,e_blur:1000/', $this->image_path);
        }

        return null;
    }

    /**
     * Get category from monument zone if not set
     */
    public function getCategoryAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Fallback to monument zone
        if ($this->monument) {
            return $this->monument->zone;
        }

        return 'General';
    }
}
