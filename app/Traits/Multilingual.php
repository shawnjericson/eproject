<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait Multilingual
{
    /**
     * Get localized attribute value
     */
    public function getLocalizedAttribute($attribute)
    {
        $locale = App::getLocale();
        $localizedAttribute = $attribute . '_' . $locale;

        // If Vietnamese version exists and current locale is Vietnamese, return it
        if ($locale === 'vi' && isset($this->attributes[$localizedAttribute]) && !empty($this->attributes[$localizedAttribute])) {
            return $this->attributes[$localizedAttribute];
        }

        // Otherwise return the default (English) version
        return $this->attributes[$attribute] ?? null;
    }
    
    /**
     * Get title in current locale (only if not already set)
     */
    public function getLocalizedTitleAttribute()
    {
        return $this->getLocalizedAttribute('title');
    }
    
    /**
     * Get localized title
     */
    public function getLocalizedTitle()
    {
        return $this->getLocalizedAttribute('title');
    }
    
    /**
     * Get localized content/description
     */
    public function getLocalizedContent()
    {
        if (isset($this->attributes['content'])) {
            return $this->getLocalizedAttribute('content');
        }
        
        if (isset($this->attributes['description'])) {
            return $this->getLocalizedAttribute('description');
        }
        
        return null;
    }
    
    /**
     * Get localized location
     */
    public function getLocalizedLocation()
    {
        return $this->getLocalizedAttribute('location');
    }
    
    /**
     * Get localized history
     */
    public function getLocalizedHistory()
    {
        return $this->getLocalizedAttribute('history');
    }
    
    /**
     * Get localized meta title
     */
    public function getLocalizedMetaTitle()
    {
        return $this->getLocalizedAttribute('meta_title') ?? $this->getLocalizedTitle();
    }
    
    /**
     * Get localized meta description
     */
    public function getLocalizedMetaDescription()
    {
        return $this->getLocalizedAttribute('meta_description');
    }
    
    /**
     * Get localized slug
     */
    public function getLocalizedSlug()
    {
        return $this->getLocalizedAttribute('slug');
    }
    
    /**
     * Check if Vietnamese version exists for an attribute
     */
    public function hasVietnameseVersion($attribute)
    {
        $vietnameseAttribute = $attribute . '_vi';
        return isset($this->attributes[$vietnameseAttribute]) && !empty($this->attributes[$vietnameseAttribute]);
    }
    
    /**
     * Get all available languages for this model
     */
    public function getAvailableLanguages()
    {
        $languages = ['en'];
        
        // Check if Vietnamese versions exist
        $vietnameseFields = ['title_vi', 'content_vi', 'description_vi'];
        foreach ($vietnameseFields as $field) {
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                $languages[] = 'vi';
                break;
            }
        }
        
        return array_unique($languages);
    }
    
    /**
     * Get completion percentage for Vietnamese translation
     */
    public function getVietnameseCompletionPercentage()
    {
        $totalFields = 0;
        $translatedFields = 0;
        
        $fieldsToCheck = [];
        
        // Check which fields exist in this model
        if (isset($this->attributes['title'])) {
            $fieldsToCheck[] = 'title';
        }
        if (isset($this->attributes['content'])) {
            $fieldsToCheck[] = 'content';
        }
        if (isset($this->attributes['description'])) {
            $fieldsToCheck[] = 'description';
        }
        if (isset($this->attributes['location'])) {
            $fieldsToCheck[] = 'location';
        }
        if (isset($this->attributes['history'])) {
            $fieldsToCheck[] = 'history';
        }
        
        foreach ($fieldsToCheck as $field) {
            $totalFields++;
            $vietnameseField = $field . '_vi';
            if (isset($this->attributes[$vietnameseField]) && !empty($this->attributes[$vietnameseField])) {
                $translatedFields++;
            }
        }
        
        return $totalFields > 0 ? round(($translatedFields / $totalFields) * 100) : 0;
    }
    
    /**
     * Scope to filter by language availability
     */
    public function scopeWithVietnamese($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('title_vi')
              ->orWhereNotNull('content_vi')
              ->orWhereNotNull('description_vi');
        });
    }
    
    /**
     * Scope to filter by incomplete Vietnamese translations
     */
    public function scopeIncompleteVietnamese($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('title_vi')
              ->orWhereNull('content_vi')
              ->orWhereNull('description_vi');
        });
    }
}
