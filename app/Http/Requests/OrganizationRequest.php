<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Organization;

class OrganizationRequest extends FormRequest
{

    protected $stopOnFirstFailure = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'integer',
            'name' => ['required', 'not_regex:#[^\w\s\.\(\),-]+#', 'max:255'],
            'email' => [
                'required', 'string', 'max:255', 'email', Rule::unique('organizations')->ignore($this->id)
            ],
            'password' => ['required_without:id', 'nullable', 'string', Password::defaults()],
            'status' => ['required', Rule::in( Organization::getClientStatuses() )],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.required_without' => 'The password field is required in case of creation',
            'status.in' => 'The email status is incorrect.',
        ];
    }
}
