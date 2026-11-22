<?php

namespace App\Services;

use App\Models\Organization;

class DoctorTimeOffService
{
    /**
     * Create a new class instance.
     */
    public function list(Organization $organization,int $doctorOrganizationId){
        $affiliation = $organization->doctorOrganizations()->where("id",$doctorOrganizationId)->firstOrFail();
        $timeOffs = $affiliation->timeOffs()->orderBy("start_at")->get();
        return $timeOffs;



    }
}
