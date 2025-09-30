<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'phone',
        'bio',
        'address',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the posts created by this user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'created_by');
    }

    /**
     * Get the monuments created by this user.
     */
    public function monuments()
    {
        return $this->hasMany(Monument::class, 'created_by');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is moderator.
     */
    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // If it's a full URL (Cloudinary), return as is
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            // If it's a local path
            return asset('storage/' . $this->avatar);
        }

        // Default avatar based on role
        return $this->role === 'admin'
            ? asset('images/default-admin-avatar.png')
            : asset('images/default-moderator-avatar.png');
    }

    /**
     * Get user's age from date of birth.
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    /**
     * Get user's full profile completion percentage.
     */
    public function getProfileCompletionAttribute()
    {
        $fields = ['name', 'email', 'avatar', 'phone', 'bio', 'address', 'date_of_birth'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }
}
