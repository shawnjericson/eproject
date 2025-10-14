<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'name',
        'email',
        'message',
        'monument_id',
        'rating',
        'status',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the monument that this feedback is for.
     */
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }

    /**
     * Mark feedback as viewed
     */
    public function markAsViewed()
    {
        $this->update(['viewed_at' => now()]);
    }

    /**
     * Scope for unviewed feedbacks
     */
    public function scopeUnviewed($query)
    {
        return $query->whereNull('viewed_at');
    }
}
