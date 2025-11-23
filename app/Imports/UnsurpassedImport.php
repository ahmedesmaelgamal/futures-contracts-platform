<?php

namespace App\Imports;

use App\Models\Investor;
use App\Models\Unsurpassed;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class UnsurpassedImport implements ToCollection, WithHeadingRow
{
    /**
     * Process the imported Excel data.
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
//        dd($collection);
       foreach ($collection as $row) {
    if (!$row->get('name')) {
        continue; // Skip rows with missing 'name'
    }

    $getInverorFromNationalId = Investor::where('national_id', $row->get('investor_national_id'))->first();

    $unsurpassed = Unsurpassed::where('national_id', $row->get('national_id'))
    ->where(column: 'office_phone',operator: VendorParentAuthData('phone'))

        ->first();

    if ($unsurpassed) {
        $unsurpassed->update([
            'name' => $row->get('name') ?? $unsurpassed->name,
            'phone' => '+966' . $row->get('phone') ?? $unsurpassed->phone,
            'national_id' => $row->get('national_id') ?? $unsurpassed->national_id,
            'investor_id' => $getInverorFromNationalId->id ?? $unsurpassed->investor_id,
            'debt' => $row->get('debt') ?? $unsurpassed->debt,
            'office_name' => VendorParentAuthData('name') ?? $unsurpassed->office_name,
            'office_phone' => (VendorParentAuthData('phone') ?? $unsurpassed->office_phone),
        ]);
    } else {
        Unsurpassed::create([
            'name' => $row->get('name'),
            'phone' => '+966' . $row->get('phone'),
            'national_id' => $row->get('national_id'),
            'investor_id' => $getInverorFromNationalId->id??null,
            'debt' => $row->get('debt')??0,
            'office_name' => VendorParentAuthData('name'),
            'office_phone' => VendorParentAuthData('phone'),
        ]);
    }
}
    }


}
