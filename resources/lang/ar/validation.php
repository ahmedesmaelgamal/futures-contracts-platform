<?php

return [

    /*
    |--------------------------------------------------------------------------
    | رسائل التحقق من الصحة
    |--------------------------------------------------------------------------
    |
    | تحتوي الأسطر التالية على رسائل الخطأ الافتراضية التي يستخدمها Laravel
    | عند التحقق من صحة البيانات. يمكنك تعديل هذه الرسائل كما تحتاج.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما يكون :other :value.',
    'active_url' => 'الحقل :attribute ليس رابطًا صحيحًا.',
    'after' => 'يجب أن يكون الحقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا مساويًا أو بعد :date.',
    'alpha' => 'يجب أن يحتوي الحقل :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي الحقل :attribute على أحرف، أرقام، شرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي الحقل :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون الحقل :attribute مصفوفة.',
    'before' => 'يجب أن يكون الحقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا مساويًا أو قبل :date.',
    'between' => [
        'numeric' => 'يجب أن يكون الحقل :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute بين :min و :max حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على بين :min و :max عناصر.',
    ],
    'boolean' => 'يجب أن يكون الحقل :attribute صحيحًا أو خطأ.',
    'confirmed' => 'تأكيد الحقل :attribute غير متطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'الحقل :attribute ليس تاريخًا صحيحًا.',
    'date_equals' => 'يجب أن يكون الحقل :attribute تاريخًا مساويًا لـ :date.',
    'date_format' => 'الحقل :attribute لا يطابق التنسيق :format.',
    'different' => 'يجب أن يكون الحقل :attribute مختلفًا عن :other.',
    'digits' => 'يجب أن يحتوي الحقل :attribute على :digits رقمًا.',
    'digits_between' => 'يجب أن يكون الحقل :attribute بين :min و :max رقمًا.',
    'dimensions' => 'الحقل :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'الحقل :attribute يحتوي على قيمة مكررة.',
    'email' => 'يجب أن يكون الحقل :attribute بريدًا إلكترونيًا صالحًا.',
    'ends_with' => 'يجب أن ينتهي الحقل :attribute بأحد القيم التالية: :values.',
    'exists' => 'القيمة المحددة للحقل :attribute غير صحيحة.',
    'file' => 'يجب أن يكون الحقل :attribute ملفًا.',
    'filled' => 'يجب أن يحتوي الحقل :attribute على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن يكون الحقل :attribute أكبر من :value.',
        'file' => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute أكبر من :value حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على أكثر من :value عناصر.',
    ],
    'gte' => [
        'numeric' => 'يجب أن يكون الحقل :attribute أكبر من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute أكبر من أو يساوي :value حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على :value عناصر أو أكثر.',
    ],
    'image' => 'يجب أن يكون الحقل :attribute صورة.',
    'in' => 'القيمة المحددة للحقل :attribute غير صحيحة.',
    'integer' => 'يجب أن يكون الحقل :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون الحقل :attribute عنوان IP صالحًا.',
    'ipv4' => 'يجب أن يكون الحقل :attribute عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن يكون الحقل :attribute عنوان IPv6 صالحًا.',
    'json' => 'يجب أن يكون الحقل :attribute نصًا بصيغة JSON صحيحة.',
    'max' => [
        'numeric' => 'يجب أن لا يكون الحقل :attribute أكبر من :max.',
        'file' => 'يجب أن لا يكون حجم الملف :attribute أكبر من :max كيلوبايت.',
        'string' => 'يجب أن لا يكون طول النص :attribute أكبر من :max حروف.',
        'array' => 'يجب أن لا يحتوي الحقل :attribute على أكثر من :max عناصر.',
    ],
    'mimes' => 'يجب أن يكون الحقل :attribute ملفًا من نوع: :values.',
    'mimetypes' => 'يجب أن يكون الحقل :attribute ملفًا من نوع: :values.',
    'min' => [
        'numeric' => 'يجب أن يكون الحقل :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute على الأقل :min حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على الأقل :min عناصر.',
    ],
    'not_in' => 'القيمة المحددة للحقل :attribute غير صحيحة.',
    'numeric' => 'يجب أن يكون الحقل :attribute رقمًا.',
    'required' => 'الحقل :attribute مطلوب.',
    'same' => 'يجب أن يتطابق الحقل :attribute مع :other.',
    'size' => [
        'numeric' => 'يجب أن يكون الحقل :attribute بحجم :size.',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :attribute :size حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على :size عناصر.',
    ],
    'string' => 'يجب أن يكون الحقل :attribute نصًا.',
    'timezone' => 'يجب أن يكون الحقل :attribute نطاقًا زمنيًا صالحًا.',
    'unique' => 'القيمة المدخلة في الحقل :attribute مستخدمة من قبل.',
    'regex' => 'صيغة الحقل :attribute غير صحيحة.',

    /*
    |--------------------------------------------------------------------------
    | أسماء الحقول بالعربية
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'national_id' => 'الرقم القومي',
        'city_id' => 'المدينة',
        'password' => 'كلمة المرور',
        'image' => 'الصورة',
    ],

];
