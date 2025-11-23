<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'period' => 'required|string|max:100',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // التحقق من الخطط الديناميكية
            'plans' => 'nullable|array|min:1',
            'plans.*.key' => 'required|string|max:255',
            'plans.*.value' => [
                'nullable',
                'required_without:plans.*.is_unlimited',
                'integer',
                function ($attribute, $value, $fail) {
                    $planIndex = explode('.', $attribute)[1] ?? null;
                    $isUnlimitedField = "plans.$planIndex.is_unlimited";

                    if (!request()->input($isUnlimitedField) && empty($value)) {
                        $fail(__('Please enter a value.'));
                    }
                },
            ],
            'plans.*.is_unlimited' => 'nullable|boolean',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'period' => 'required|string|max:100',
            'discount' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'plans' => 'nullable|array',
            'plans.*.key' => 'required|string',
            'plans.*.value' => 'required|string',
            'plans.*.is_unlimited' => 'nullable|boolean',
        ];
    }
}
