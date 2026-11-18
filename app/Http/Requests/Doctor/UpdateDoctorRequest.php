<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $organization = $this->attributes->get("currentOrganization");
        return [
            "branch_id" => ["sometimes", "nullable", "integer", Rule::exists("branches", "id")->where("organization_id", $organization->id)->where("status", "active")],
            "department_id" => ["sometimes", "nullable", "integer", Rule::exists("departments", "id")->where("organization_id", $organization->id)->where("status", "active")],
            "staff_number" => "sometimes|nullable|string|max:255",
            "status" => ["sometimes", "string", Rule::in(["active", "inactive"])]

        ];
    }
}