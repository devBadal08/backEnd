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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function education()
    {
        return $this->hasMany(ProfileEducation::class);
    }
}
