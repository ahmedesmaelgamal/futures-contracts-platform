<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;

class UnsurpassedExampleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data' => new DataSheet(),
            'Instructions' => new InstructionsSheet(),
        ];
    }
}

class DataSheet implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                ['مكتب أجل', '123456789', '9876543210', '9874563211', '1400'],
                ['شركة النور', '512345678', '1111111111', '2222222222', '2000'],
                ['مؤسسة السلام', '534567891', '3333333333', '4444444444', '3000'],
                ['مكتب الوفاء', '556789123', '5555555555', '6666666666', '2500'],
                ['التميز للاستثمار', '578912345', '7777777777', '8888888888', '1800'],
                ['شركة الرؤية', '590123456', '9999999999', '1010101010', '3200'],
                ['مؤسسة الريادة', '501234567', '1212121212', '1313131313', '1500'],
                ['مكتب الأمل', '512345679', '1414141414', '1515151515', '2700'],
                ['شركة البيان', '523456789', '1616161616', '1717171717', '1900'],
                ['الصفوة المالية', '534567890', '1818181818', '1919191919', '4100'],
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'phone',
            'national_id',
            'investor_national_id',
            'debt',
        ];
    }
}

class InstructionsSheet implements FromArray
{
    public function array(): array
    {
        return [
            ['تعليمات'],
            ['1. قم بتعبئة البيانات في صفحة "Data"'],
            ['2. سيتم تلقائيًا إضافة المقدمة +966 لأرقام الجوال'],
            ['3. لا تقم بتعديل صف العناوين (رؤوس الأعمدة)'],
            ['4. يجب أن يكون رقم الهوية فريدًا'],
            ['5. قم بحذف صفحة التعليمات هذه قبل الرفع'],
            ['6. investor_national_id يجب أن يكون هو رقم هوية المستثمر الذي ترغب بإضافة القرض له'],
            ['7. debt يجب أن يحتوي على قيمة القرض'],
        ];
    }
}
