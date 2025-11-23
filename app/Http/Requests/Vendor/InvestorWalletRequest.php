<?php

namespace App\Http\Requests\Vendor;

use App\Models\Investor;
use Illuminate\Foundation\Http\FormRequest;

class InvestorWalletRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
      return [
          'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->input('type') == 1) {
                        $investor =Investor::find($this->input('investor_id'));
                        if ($value > $investor->balance) {
                            $fail(' لا يوجد رصيد كافي في حساب هذا المستثمر  الرصيد المتاح هو ' . $investor->balance . ' ريال');
                        }
                    }
                },
            ],
            'type' => 'required|in:0,1',
            'investor_id'=>'required|exists:investors,id',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'هذا الحقل مطلوب',
            'amount.numeric' => 'هذا الحقل يجب ان يكون رقم',
            'type.required' => 'هذا الحقل مطلوب',
            'type.in' => 'هذا الحقل يجب ان يكون رقم',
            'investor_id.required' => 'هذا الحقل مطلوب',
            'investor_id.exists' => 'المستثمر غير موجود',
            'note.string' => 'هذا الحقل يجب ان يكون نص',
            'note.max' => 'هذا الحقل يجب ان لا يتجاوز 255 حرف',

        ];
    }
}
