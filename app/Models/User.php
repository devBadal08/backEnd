<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'max_profiles_limit',
        'phone',
        // 'alt_phone',
        'email',
        'role',
        'created_by',
        'password',
        // 'image',        
    ];

    public function scopeFilterBySearch($query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', "%$searchTerm%")
                    ->orWhere('last_name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%")
                    ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }
        return $query;
    }

    public function profiles()
    {
        $url = asset('images/profiles/');
        return $this->hasMany(Profile::class, 'user_id')
            ->selectRaw("*, CONCAT('$url', '/', image) AS image_url");
    }

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

    ];
}
