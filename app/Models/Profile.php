<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Profile as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Profile extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'gender',
        'phone',
        'alt_phone',
        'email',
        'image',
        'role',
        'created_by',
        'password',
        'username',
        'marital_status',
        'height',
        'weight',
        'hobbies',
        'about_self',
        'about_job',
        'education',
        'age',
        'village',
        'city',
        'state',
        // Other profile fields
    ];

    public function scopeFilterBySearch($query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function($query) use ($searchTerm) {
                $query->where('first_name', 'like', "%$searchTerm%")
                      ->orWhere('middle_name', 'like', "%$searchTerm%")
                      ->orWhere('last_name', 'like', "%$searchTerm%")
                      ->orWhere('email', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }
        return $query;
    }

    public function scopeFilterByAge($query, $minAge)
    {
        if ($minAge) {
            return $query->where('age', '>=', $minAge);
        }
        return $query;
    }

    public function scopeFilterByBirthYear($query, $birthYear)
    {
        if ($birthYear) {
            return $query->whereYear('dob', $birthYear);
        }
        return $query;
    }

    public function scopeFilterByGender($query, $gender)
    {
        if ($gender) {
            return $query->where('gender', $gender);
        }
        return $query;
    }

    public function scopeFilterByLocation($query, $village, $city, $state)
    {
        $filters = [];
        if ($village) {
            $filters[] = ['village', 'like', "%$village%"];
        }
        if ($city) {
            $filters[] = ['city', 'like', "%$city%"];
        }
        if ($state) {
            $filters[] = ['state', $state];
        }

        return $query->where($filters);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(ProfileEducation::class, 'profile_id');
    }
}
