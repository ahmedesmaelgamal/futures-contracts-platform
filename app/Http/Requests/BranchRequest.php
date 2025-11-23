<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'required|unique:branches,name',
            'address' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => $this->name == 'الفرع الرئيسي' ? 'nullable' : 'nullable|unique:branches,name,' . $this->branch,
            'address' => 'required',
        ];
    }
}
