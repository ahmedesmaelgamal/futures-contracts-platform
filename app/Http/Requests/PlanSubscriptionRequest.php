<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanSubscriptionRequest extends FormRequest
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
            'vendor_id' => 'required|exists:vendors,id',
            'plan_id' => 'required|exists:plans,id',
            'from' => 'required|date',
            'to' => 'required|date',
            'payment_receipt' => 'nullable|image',

        ];
    }

    protected function update(): array
    {
        return [
            'vendor_id' => 'nullable|exists:vendors,id',
            'plan_id' => 'nullable|exists:plans,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'payment_receipt' => 'nullable|image',
        ];
    }
}