<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
        return [
            'name' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'commercial_number' => 'required',
            'city_id' => 'required|exists:cities,id',
            'profit_ratio' => 'required|numeric',
            'is_profit_ratio_static' => 'required|boolean',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'loader' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'fav_icon'=>'nullable|image|mimes:jpeg,png,jpg,gif',
        ];

    }




}
