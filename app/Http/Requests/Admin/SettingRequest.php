<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'key' => 'required|string',
            'value' => 'nullable|string',
        ];
    }

    protected function update(): array
    {
        return [
            'key' => 'required|string',
            'value' => 'nullable|string',
        ];
    }
}