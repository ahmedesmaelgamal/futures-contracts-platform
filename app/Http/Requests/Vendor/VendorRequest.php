<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * This ensures that the phone number is stored with +966.
     */
    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => '+966' . preg_replace('/^0+/', '', $this->phone)
            ]);
        }
    }

    /**
     * Determine the validation rules.
     */
    public function rules()
    {
        return $this->isMethod('put') ? $this->update() : $this->store();
    }

    /**
     * Validation rules for store request.
     */
    protected function store(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => [
                'required',
                'string',
                'regex:/^\+966\d{9}$/',
                'unique:vendors,phone'
            ],
            'city_id' => 'required|exists:cities,id',
            'national_id' => 'required|numeric|digits:10|unique:vendors,national_id',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'permissions' => 'required|array',
            'branch_ids' => 'required|array',
        ];
    }

    /**
     * Validation rules for update request.
     */
    protected function update(): array
    {
        return [
            'id' => 'required|exists:vendors,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:vendors,email,' . $this->id,
            'phone' => [
                'required',
                'string',
                'regex:/^\+966\d{9}$/',
                'unique:vendors,phone,' . $this->id
            ],
            'national_id' => 'nullable|numeric|digits:10|unique:vendors,national_id,' . $this->id,
            'city_id' => 'required|exists:cities,id',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'permissions' => 'required|array',
            'branch_ids' => 'required|array',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone.required' => 'The phone field is required.',
            'phone.regex' => 'The phone number must be in the format +966XXXXXXXXX.',
            'phone.unique' => 'The phone number has already been taken.',
            'city_id.required' => 'The city field is required.',
            'city_id.exists' => 'The selected city is invalid.',
            'national_id.required' => 'The national ID field is required.',
            'national_id.numeric' => 'The national ID must be a number.',
            'national_id.digits' => 'The national ID must be 10 digits.',
            'national_id.unique' => 'The national ID has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'image.image' => 'The image must be an image file.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, gif.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
            'permissions.required' => 'The permissions field is required.',
            'permissions.array' => 'The permissions must be an array.',
            'branch_ids.required' => 'The branch IDs field is required.',
            'branch_ids.array' => 'The branch IDs must be an array.',
        ];

    }
}
