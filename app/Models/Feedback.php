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
    ];

    /**
     * Get the monument that this feedback is for.
     */
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}
