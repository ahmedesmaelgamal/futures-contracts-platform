<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'national_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'investor_id' => 'required|exists:investors,id',
            'quantity' => 'required|numeric',
            'expected_price' => 'required|numeric',
            'Total_expected_commission' => 'required|numeric',
            'investor_commission' => 'required|numeric',
            'vendor_commission' => 'required|numeric',
            'sell_diff' => 'required|numeric',
            'delivered_price_to_client' => 'required|numeric',
            'required_to_pay' => 'required|numeric',
            'date' => 'required|date',
            'is_installment' => 'nullable',
            'installment_number' => 'required_if:is_installment,on|nullable',


        ];
    }

    protected function update(): array
    {
        return [

        ];
    }
}
