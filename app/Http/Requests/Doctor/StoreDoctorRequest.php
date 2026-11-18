<?php

namespace App\Http\Requests\Doctor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organization = $this->attributes->get(
            'currentOrganization'
        );
        $existingUser = User::where("email", $this->input("email"))->first();
        $newUser = !$existingUser;

        return [
            'name' => [Rule::requiredIf($newUser), "string", "max:255"],
            'email' => 'required|email|max:255',
            'phone' => [Rule::requiredIf($newUser), "string", "max:20"],
            'password' => 'nullable|string|min:8',

            'license_number' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',

            'branch_id' => [
                'nullable',
                Rule::exists('branches', 'id')
                    ->where('organization_id', $organization->id)
                    ->where('status', 'active'),
            ],

            'department_id' => [
                'nullable',
                Rule::exists('departments', 'id')
                    ->where('organization_id', $organization->id)
                    ->where('status', 'active'),
            ],

            'staff_number' => 'nullable|string|max:255',
        ];
    }
}