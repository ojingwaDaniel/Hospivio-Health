<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'qualification',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctorOrganizations()
    {
        return $this->hasMany(DoctorOrganization::class);
    }
}