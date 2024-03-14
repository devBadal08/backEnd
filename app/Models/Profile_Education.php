<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile_Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'type',
        'institution_name',
        'degree',
        'field_of_study',
        'start_year',
        'end_year',
    ];

    public function profile()
    {
        return $this->belongsTo(User::class);
    }
}
