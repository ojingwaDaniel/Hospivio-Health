<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorAvailabilityRequest;
use App\Http\Requests\Doctor\UpdateDoctorAvailabilityRequest;
use App\Http\Resources\DoctorAvailabilityResource;
use App\Services\DoctorAvailabilityService;
use Illuminate\Http\Request;

class DoctorAvailabilityController extends Controller
{
    public function __construct(private DoctorAvailabilityService $service) {}

    public function index(Request $request)
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId = (int) $request->route("doctorOrganizationId");
        $availabilities = $this->service->list($organization,$doctorOrganizationId);

        return DoctorAvailabilityResource::collection($availabilities);


    }
    public function store(StoreDoctorAvailabilityRequest $request)
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId = (int)$request->route("doctorOrganizationId");
        $doctorAvailability = $this->service->create($organization, $doctorOrganizationId, $request->validated());
        return response()->json([
            "message" => "Doctor availability created successfully.",
            "doctorAvailability" => $doctorAvailability
        ], 201);
    }

    public function update(UpdateDoctorAvailabilityRequest $request)
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId = (int)$request->route("doctorOrganizationId");
        $availabilityId = (int) $request->route("availabilityId");
        $doctorAvailability = $this->service->update($organization, $doctorOrganizationId, $availabilityId, $request->validated());
        return response()->json([
            "message" => "Doctor availability updated successfully.",
            "doctorAvailability" => $doctorAvailability
        ]);
    }
    public function destroy(Request $request){
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId =(int) $request->route("doctorOrganizationId");
        $availabilityId =(int) $request->route("availabilityId");
        $this->service->delete($organization,$doctorOrganizationId,$availabilityId);
        return response()->json([
            "message" => "Doctor Availability Deleted successfully"
        ]);
    }
}
