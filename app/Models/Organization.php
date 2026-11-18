<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'type',
        'status',
    ];
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(["role", "status"])->withTimestamps();
    }
    public function domain()
    {
        return $this->hasOne(Domain::class);
    }
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    public function doctorOrganizations()
    {
        return $this->hasMany(DoctorOrganization::class);
    }
}
