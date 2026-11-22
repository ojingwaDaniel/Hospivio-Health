<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DoctorOrganization extends Model
{
    protected $fillable = ["doctor_id", "organization_id", "branch_id", "department_id", "staff_number", "status"];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }
    public function timeOffs()
    {
        return $this->hasMany(DoctorTimeOff::class);
    }
}
