<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'history',
        'content',
        'location',
        'map_embed',
        'zone',
        'latitude',
        'longitude',
        'is_world_wonder',
        'image',
        'created_by',
        'status',
    ];

    /**
     * Get the user who created this monument.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the gallery images for this monument.
     */
    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get the feedbacks for this monument.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get the translations for this monument.
     */
    public function translations()
    {
        return $this->hasMany(MonumentTranslation::class);
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
        return $translation ? $translation->description : $this->description;
    }

    /**
     * Get history in specific language.
     */
    public function getHistory($language = 'en')
    {
        $translation = $this->translation($language);
        return $translation ? $translation->history : $this->history;
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
     * Get location in specific language.
     */
    public function getLocation($language = 'en')
    {
        $translation = $this->translation($language);
        return $translation ? $translation->location : $this->location;
    }

    /**
     * Scope for approved monuments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for monuments by zone.
     */
    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    /**
     * Scope for world wonders.
     */
    public function scopeWorldWonders($query)
    {
        return $query->where('is_world_wonder', true);
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
