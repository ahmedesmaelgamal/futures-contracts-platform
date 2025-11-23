<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {return [
            'notes' => 'nullable|string|max:255',
            'paid'=>'nullable',
            'grace_period'=>'nullable',
            'id'=>'required|exists:orders,id',

        ];
    }


}
