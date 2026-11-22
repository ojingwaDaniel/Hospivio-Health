<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorTimeOff extends Model
{

  use SoftDeletes;
    protected $fillable = [
        'doctor_organization_id',
        'start_at',
        'end_at',
        'reason',
    ];
    public function doctorOrganization(){
        return $this->belongsTo(DoctorOrganization::class);
    }

}
