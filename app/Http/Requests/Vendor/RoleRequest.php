<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
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
        if (request()->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'name' => 'required',
            'guard_name' => 'required|in:admin,vendor',
            'permissions' => 'required|array',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required',
            'guard_name' => 'required|in:admin,vendor',
            'permissions' => 'required|array',
        ];
    }
}
