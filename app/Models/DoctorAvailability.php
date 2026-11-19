<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorAvailability extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'doctor_organization_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function doctorOrganization(): BelongsTo
    {
        return $this->belongsTo(DoctorOrganization::class);
    }
}
