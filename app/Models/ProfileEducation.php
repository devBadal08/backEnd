<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileEducation extends Model
{
    use HasFactory;

    protected $table = 'profile_education';

    protected $fillable = [
        'profile_id',
        'type',
        'organization_name',
        'degree',
        'start_year',
        'end_year',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
