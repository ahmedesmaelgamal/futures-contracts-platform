<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnsurpassedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => '+966' . preg_replace('/^0+/', '', $this->phone)
            ]);
        }


        if ($this->has('office_phone')) {
            $this->merge([
                'office_phone' => '+966' . preg_replace('/^0+/', '', $this->office_phone)
            ]);
        }
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/',
            'national_id' => 'required|numeric|digits:10',
            'office_name' => 'nullable|string|max:255',
            'office_phone' => 'nullable|string|regex:/^\+966[0-9]{9}$/',
            'investor_id' => 'required|exists:investors,id',
            'debt' => 'required|numeric|min:0.01',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/',
            'national_id' => 'required|numeric|digits:10',
            'office_name' => 'nullable|string|max:255',
            'office_phone' => 'nullable',
            'investor_id' => 'required|exists:investors,id',
            'debt' => 'required|numeric|min:0.01',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'يجب أن يحتوي رقم الهاتف على 9 أرقام فقط.',
            'office_phone.regex' => ' يجب أن يحتوي رقم الهاتف على 9 أرقام فقط.',
            'investor_id.exists' => 'لم يتم العثور على المستثمر المطلوب.',
            'investor_id.required' => 'حقل المستثمر مطلوب.',
            'debt.required' => 'حقل المبلغ الطلوب سداده مطلوب.',
            'debt.min' => 'حقل المبلغ الطلوب سداده يجب ان يكون اكبر من 0.01',


        ];
    }
}
