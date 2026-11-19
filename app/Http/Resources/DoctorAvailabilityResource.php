<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorAvailabilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'doctor_organization_id' => $this->doctor_organization_id,
            'day_of_week'            => $this->day_of_week,
            'start_time'             => $this->start_time,
            'end_time'               => $this->end_time,
        ];
    }
}