<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
