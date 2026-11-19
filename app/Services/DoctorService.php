<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\DoctorOrganization;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use stdClass;

class DoctorService
{
    public function create(Organization $organization, array $data)
    {
        return DB::transaction(function () use ($organization, $data) {

            // 1. Check whether this email already belongs to a user.
            $user = User::where('email', $data['email'])
                ->lockForUpdate()
                ->first();

            // 2. If the user exists, check whether they are a doctor.
            if ($user) {

                if (!$user->doctor) {
                    abort(
                        409,
                        'This email already belongs to a non-doctor account. Please review the account first.'
                    );
                }

                $doctor = $user->doctor;
            } else {


                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'password' => $data['password'],
                ]);


                $doctor = Doctor::create([
                    'user_id' => $user->id,
                    'license_number' => $data['license_number'] ?? null,
                    'qualification' => $data['qualification'] ?? null,
                ]);
            }

            $alreadyRegistered = $organization->doctorOrganizations()
                ->where('doctor_id', $doctor->id)
                ->exists();

            if ($alreadyRegistered) {
                abort(
                    409,
                    'This doctor is already registered with this hospital.'
                );
            }


            $affiliation = DoctorOrganization::create([
                'doctor_id' => $doctor->id,
                'organization_id' => $organization->id,
                'branch_id' => $data['branch_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'staff_number' => $data['staff_number'] ?? null,
                'status' => 'active',
            ]);


            return $affiliation->load([
                'doctor.user',
                'branch',
                'department',
            ]);
        });
    }
    public function update(Organization $organization, array $data,$id)
    {
        $affiliation = $organization->doctorOrganizations()->where("id",$id)->firstOrFail();
        $affiliation->update($data);
        return $affiliation->load([
            "doctor.user",
            "department",
        ]);
    }
}
