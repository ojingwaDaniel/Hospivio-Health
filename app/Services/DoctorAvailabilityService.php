<?php

namespace App\Services;

use App\Models\DoctorAvailability;
use App\Models\DoctorOrganization;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DoctorAvailabilityService
{
    public function list(Organization $organization, int $doctorOrganizationId)
    {
        $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->firstOrFail();
        $availabilities = $affiliation->availabilities()->orderBy("day_of_week")->orderBy("start_time")->get();
        return $availabilities;
    }
    public function create(Organization $organization, int $doctorOrganizationId, array $data)
    {
        return DB::transaction(function () use ($organization, $doctorOrganizationId, $data) {
            $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $overlapExists = $affiliation->availabilities()->where("day_of_week", $data["day_of_week"])->where("start_time", "<", $data["end_time"])->where("end_time", ">", $data["start_time"])->exists();
            if ($overlapExists) {
                throw ValidationException::withMessages([
                    'start_time' => [
                        'This availability overlaps with an existing schedule.',
                    ],
                ]);
            }
            return $affiliation->availabilities()->create([
                'day_of_week' => $data['day_of_week'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
            ]);
        });
    }


    public function update(Organization $organization, int $doctorOrganizationId, int  $availabilityId, array $data)
    {
        return DB::transaction(function () use ($organization, $doctorOrganizationId, $availabilityId, $data) {
            $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $availability = $affiliation->availabilities()->where("id", $availabilityId)->lockForUpdate()->firstOrFail();
            $day_of_week = $data["day_of_week"] ?? $availability->day_of_week;
            $start_time = $data["start_time"] ??substr($availability->start_time,0,5);
            $end_time = $data["end_time"] ?? substr($availability->end_time,0,5);
            if ($end_time <= $start_time) {
                throw ValidationException::withMessages([
                    "message" => "End Time has to come after start time"
                ]);
            }

            $overlapExists = $affiliation->availabilities()->where("id", "!=", $availability->id)->where("day_of_week", $day_of_week)->where("start_time", "<", $end_time)->where("end_time", ">", $start_time)->exists();
            if ($overlapExists) {
                throw ValidationException::withMessages([
                    'start_time' => [
                        'This availability overlaps with an existing schedule.',
                    ],
                ]);
            }
            $availability->update([
                "day_of_week" => $day_of_week,
                "start_time" => $start_time,
                "end_time" => $end_time
            ]);
            return $availability;
        });
    }
    public function delete(
        Organization $organization,
        int $doctorOrganizationId,
        int $availabilityId
    ) {
        return DB::transaction(function () use (
            $organization,
            $doctorOrganizationId,
            $availabilityId
        ) {
            $affiliation = $organization->doctorOrganizations()
                ->where("id", $doctorOrganizationId)
                ->lockForUpdate()
                ->firstOrFail();

            $availability = $affiliation->availabilities()
                ->where("id", $availabilityId)
                ->lockForUpdate()
                ->firstOrFail();

            $availability->delete();

            return $availability;
        });
    }

    public function restore( Organization $organization,
    int $doctorOrganizationId,
    int $availabilityId){
        return DB::transaction(function() use($organization,$doctorOrganizationId,$availabilityId){
            $affiliation = $organization->doctorOrganizations()->where("id",$doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $restoreAvailability = $affiliation->availabilities()->withTrashed()->where("id",$availabilityId)->lockForUpdate()->firstOrFail();
            $overlapExists = $affiliation->availabilities()->where("day_of_week", $restoreAvailability->day_of_week)->where("start_time", "<", $restoreAvailability->end_time)->where("end_time", ">", $restoreAvailability->start_time)->exists();
            if ($overlapExists) {
                throw ValidationException::withMessages([
                    'start_time' => [
                        'This availability overlaps with an existing schedule.',
                    ],
                ]);
            }
            $restoreAvailability->restore();
            return $restoreAvailability;


        });
    }
}