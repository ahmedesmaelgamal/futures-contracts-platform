<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'name' => 'required|unique:cities,name',
            'country_id' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'nullable|unique:cities,name,' .$this->city,
            'country_id' => 'nullable',
        ];
    }


    public function messages()
    {
//        return [
//            'name[ar].required' => trans('name city required ar'),
//            'name[en].required' => trans('name city required en'),
//            'name[ar].unique' => trans('name city must be unique'),
//            'name[en].unique' => trans('name city must be unique'),
//            'country_id.required' => trans('name country required'),
//            'location.required' => trans('name location required'),
//        ];
    }
}
