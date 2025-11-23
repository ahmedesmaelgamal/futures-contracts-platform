<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'operation' => 'required|in:0,1',
            'investor_id' => 'required|exists:investors,id',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|numeric',
            'total_price_add' => 'required_if:operation,1',
            'vendor_commission' => 'required_if:operation,1',
            'investor_commission' => 'required_if:operation,1',
            'sell_diff' => 'required_if:operation,1',
            'total_price_sub' => 'required_if:operation,0',
        ];

    }

    public function messages()
    {
        return [
            'operation.required' => 'يجب تحديد نوع العملية',
            'operation.in' => 'يجب تحديد نوع العملية',
            'investor_id.required' => 'يجب تحديد المستثمر',
            'investor_id.exists' => 'المستثمر غير موجود',
            'category_id.required' => 'يجب تحديد القسم',
            'category_id.exists' => 'القسم غير موجود',
            'branch_id.required' => 'يجب تحديد الفرع',
            'branch_id.exists' => 'الفرع غير موجود',
            'quantity.required' => 'يجب تحديد الكمية',
            'quantity.numeric' => 'يجب تحديد الكمية',
            'total_price_add.required_if' => 'يجب تحديد السعر',
            'vendor_commission.required_if' => 'يجب تحديد العمولة',
            'investor_commission.required_if' => 'يجب تحديد العمولة',
            'sell_diff.required_if' => 'يجب تحديد الفرق',
            'total_price_sub.required_if' => 'يجب تحديد السعر',
            
        ];
    }




}
