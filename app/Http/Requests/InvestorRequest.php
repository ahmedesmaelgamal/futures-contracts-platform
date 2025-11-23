<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestorRequest extends FormRequest
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
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:9',
            'branch_id' => 'required:exists:branches,id',
            'national_id' => 'required|numeric|digits:10'
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:9',
            'branch_id' => 'required:exists:branches,id',
            'national_id' => 'required|numeric|digits:10'

        ];
    }
}
