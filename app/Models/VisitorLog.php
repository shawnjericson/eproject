<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * Check if IP has visited within the last 24 hours
     */
    public static function hasVisitedRecently($ipAddress, $hours = 24)
    {
        return static::where('ip_address', $ipAddress)
            ->where('visited_at', '>=', Carbon::now()->subHours($hours))
            ->exists();
    }

    /**
     * Log a new visitor
     */
    public static function logVisitor($ipAddress, $userAgent = null)
    {
        return static::create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'visited_at' => Carbon::now(),
        ]);
    }

    /**
     * Get unique visitor count
     */
    public static function getUniqueVisitorCount()
    {
        return static::distinct('ip_address')->count('ip_address');
    }

    /**
     * Get total visit count
     */
    public static function getTotalVisitCount()
    {
        return static::count();
    }
}
