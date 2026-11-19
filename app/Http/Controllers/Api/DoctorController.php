<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Models\DoctorOrganization;
use App\Services\DoctorService;
use Illuminate\Http\Request;


class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->attributes->get(
            'currentOrganization'
        );

        $doctors = $organization->doctorOrganizations()
            ->with(['doctor.user', 'branch', 'department'])
            ->where('status', 'active')
            ->get();

        return response()->json([
            'doctors' => $doctors,
        ]);
    }

    public function store(StoreDoctorRequest $request, DoctorService $doctorService)
    {
        $organization =  $request->attributes->get("currentOrganization");
        $doctor = $doctorService->create($organization, $request->validated());
        return response()->json([
            "doctor" => $doctor
        ], 201);
    }
    public function update(UpdateDoctorRequest $request, DoctorService $doctorService)
    {
        $organization = $request->attributes->get("currentOrganization");
        $id = $request->route("id");

        $doctor = $doctorService->update($organization, $request->validated(), (int)$id);
        return response()->json([
            "message" => "Doctor affiliation updated successfully.",
            "doctor" => $doctor
        ]);
    }
}
