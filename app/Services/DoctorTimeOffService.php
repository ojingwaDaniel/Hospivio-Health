<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DoctorTimeOffService
{
    /**
     * Create a new class instance.
     */
    public function list(Organization $organization, int $doctorOrganizationId)
    {
        $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->firstOrFail();
        $timeOffs = $affiliation->timeOffs()->orderBy("start_at")->get();
        return $timeOffs;
    }
    public function create(Organization $organization, int $doctorOrganizationId, array $data)
    {
        return DB::transaction(function () use ($organization, $doctorOrganizationId, $data) {
            $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $overLapExists = $affiliation->timeOffs()->where("start_at", "<", $data["end_at"])->where("end_at", ">", $data["start_at"])->exists();
            if ($overLapExists) {
                throw ValidationException::withMessages([
                    "start_at" => [
                        "This time-off period overlaps with an existing schedule."
                    ]
                ]);
            }
            $timeOffRecord =  $affiliation->timeOffs()->create([
                "start_at" => $data["start_at"],
                "end_at" => $data["end_at"],
                "reason" => $data["reason"] ?? null
            ]);
            return $timeOffRecord;
        });
    }
    public function update(Organization $organization, int $doctorOrganizationId, int $TimeOffId, array $data)
    {
        return DB::transaction(function () use ($organization, $doctorOrganizationId, $TimeOffId, $data) {
            $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $timeOff = $affiliation->timeOffs()->where("id", $TimeOffId)->lockForUpdate()->firstOrFail();
            $startAt = $data["start_at"] ?? $timeOff->start_at;
            $endAt = $data["end_at"] ?? $timeOff->end_at;
            if ($endAt <= $startAt) {
                throw ValidationException::withMessages([
                    "end_at" => [
                        "The end date must be later than the start date."
                    ]
                ]);
            }
            $overLapExists = $affiliation->timeOffs()
                ->where("id", "!=", $timeOff->id)
                ->where("start_at", "<", $endAt)
                ->where("end_at", ">", $startAt)
                ->exists();
            if ($overLapExists) {
                throw ValidationException::withMessages([
                    "start_at" => [
                        "This time-off period overlaps with an existing schedule."
                    ]
                ]);
            }

            $timeOff->update([
                "start_at" => $startAt,
                "end_at" => $endAt,
                "reason" => array_key_exists("reason", $data) ? $data["reason"] : $timeOff->reason,
            ]);
            return $timeOff->fresh();
        });
    }
    public function delete(Organization $organization, int $doctorOrganizationId, int $TimeOffId)
    {
        return DB::transaction(function () use ($organization, $doctorOrganizationId, $TimeOffId) {
            $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $timeOff = $affiliation->timeOffs()->where("id", $TimeOffId)->lockForUpdate()->firstOrFail();
            $timeOff->delete();
            return $timeOff;
        });
    }
    public function restore(
        Organization $organization,
        int $doctorOrganizationId,
        int $timeOffId
    ) {
        return DB::transaction(function () use ($organization, $doctorOrganizationId, $timeOffId) {
            $affiliation = $organization->doctorOrganizations()->where("id", $doctorOrganizationId)->lockForUpdate()->firstOrFail();
            $restoreTimeOff = $affiliation->timeOffs()->withTrashed()->where("id", $timeOffId)->lockForUpdate()->firstOrFail();
            $overlapExists = $affiliation->timeOffs()->where("start_at", "<", $restoreTimeOff->end_at)->where("end_at", ">", $restoreTimeOff->start_at)->exists();
            if ($overlapExists) {
                throw ValidationException::withMessages([
                    'start_at' => [
                        'This timeOff record overlaps with an existing schedule.',
                    ],
                ]);
            }
            $restoreTimeOff->restore();
            return $restoreTimeOff;
        });
    }
}