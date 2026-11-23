<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorTimeOffRequest;
use App\Http\Requests\Doctor\UpdateDoctorTimeOffRequest;
use App\Services\DoctorTimeOffService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorTimeOffController extends Controller
{
    public function __construct(private DoctorTimeOffService $service) {}
    public function index(Request $request): JsonResponse
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId = $request->route("doctorOrganizationId");
        $timeOffList = $this->service->list($organization, $doctorOrganizationId);
        return response()->json([
            "timeOffs" => $timeOffList
        ]);
    }

    public function store(StoreDoctorTimeOffRequest $request): JsonResponse
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId = $request->route("doctorOrganizationId");
        $timeOff = $this->service->create($organization, $doctorOrganizationId, $request->validated());
        return response()->json([
            "message"=> "TimeOff record created Successfully",
            "timeOff" => $timeOff
        ]);
    }

    public function update(UpdateDoctorTimeOffRequest $request): JsonResponse
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId =  $request->route("doctorOrganizationId");
        $timeOffId =  $request->route("timeOffId");
        $timeOff = $this->service->update($organization, $doctorOrganizationId, $timeOffId, $request->validated());
        return response()->json([
            "timeOff" => $timeOff
        ]);
    }
    public function destroy(Request $request): JsonResponse
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId =  $request->route("doctorOrganizationId");
        $timeOffId =  $request->route("timeOffId");
        $this->service->delete($organization, $doctorOrganizationId, $timeOffId);
        return response()->json([
            "message" => "Time off record deleted Successfully"
        ]);
    }
    public function restore(Request $request): JsonResponse
    {
        $organization = $request->attributes->get("currentOrganization");
        $doctorOrganizationId =  $request->route("doctorOrganizationId");
        $timeOffId =  $request->route("timeOffId");
        $this->service->restore($organization, $doctorOrganizationId, $timeOffId);
        return response()->json([
            "message" => "Time off record restored Successfully"
        ]);
    }
}
