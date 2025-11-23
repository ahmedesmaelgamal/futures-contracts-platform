<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'required|numeric|digits:9',
            'national_id' => 'required|numeric|digits:10',
            'branch_id' => 'required|exists:branches,id',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required|numeric|digits:9',
            'national_id' => 'required',
            'branch_id' => 'required|exists:branches,id',
        ];
    }
}
