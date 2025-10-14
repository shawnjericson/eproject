<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'created_by',
        'status',
        'published_at',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created this post.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'approved')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for pending posts.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved posts.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get the translations for this post.
     */
    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }

    /**
     * Get translation for specific language.
     */
    public function translation($language = 'en')
    {
        return $this->translations()->where('language', $language)->first();
    }

    /**
     * Get title in specific language or fallback.
     */
    public function getTitle($language = 'en')
    {
        $translation = $this->translation($language);
        return $translation ? $translation->title : $this->title;
    }

    /**
     * Get description in specific language.
     */
    public function getDescription($language = 'en')
    {
        $translation = $this->translation($language);
        return $translation ? $translation->description : null;
    }

    /**
     * Get content in specific language or fallback.
     */
    public function getContent($language = 'en')
    {
        $translation = $this->translation($language);
        return $translation ? $translation->content : $this->content;
    }

    /**
     * Get the image URL - handles both local storage and Cloudinary URLs
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // If it's already a full URL (Cloudinary), return as is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // If it's a local path, add storage prefix
        return asset('storage/' . $this->image);
    }
}
