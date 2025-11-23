<?php

namespace App\Services\Admin;

use App\Models\Setting as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Validator;
class SettingService extends BaseService
{
    protected string $folder = 'admin/setting';
    protected string $route = 'settings';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {

        $settings=$this->model->all();

            return view($this->folder . '/index', [
                'updateRoute' => route("{$this->route}.update", ['scheme' => 'https']),
                'bladeName' => ($this->route),
                'route' => $this->route,
                'settings'=>$settings
            ]);

    }


    public function update($data)
    {
//        dd($data);
        Validator::make($data, [
            'iban' => [
                'required_if:bank_name,account_holder',
                'regex:/^SA\d{2}(\s\d{4}){5}$/'
            ],
        ], [
            'iban.regex' => 'يجب أن يكون رقم الآيبان بصيغة SAXX XXXX XXXX XXXX XXXX XXXX',
        ]);
        try {

            // Handle file uploads
            $files = ['logo', 'fav_icon', 'loader'];
            foreach ($files as $file) {
                if (isset($data[$file])) {
                    $filePath = $data[$file]->store('public/settings');
                    $this->storeOrUpdateSetting($file, basename($filePath));
                }
            }
            if (isset($data['bank_name'])) {
                $this->storeOrUpdateSetting('bank_name', $data['bank_name']);
            }
            if (isset($data['account_holder'])) {
                $this->storeOrUpdateSetting('account_holder', $data['account_holder']);
            }
            if (isset($data['iban'])) {
                $this->storeOrUpdateSetting('iban', $data['iban']);
            }

            if (!empty($data['phones']) && is_array($data['phones'])) {
                $this->model->where('key', 'like', 'phone%')->delete();

                for ($index = 0; $index < count($data['phones']); $index++) {
                    $phone = $data['phones'][$index];
                    // check is phone number is unique
                    $phoneExists = $this->model->where('value', $phone)->exists();
                    if (!$phoneExists) {


                    if (!empty($phone)) {
                        $this->model->updateOrCreate([
                            'key' => 'phone'.$index,
                            'value' => $phone
                        ]);
                    }
                    }
                }
            }
            $other=[
                'iban'=>$data['iban']??null,
                'bank_name'=>$data['bank_name']??null,
                'account_holder'=>$data['account_holder']??null,
            ];

            foreach ($other as $key => $value) {
                if (!empty($value)) {
                    $this->model->updateOrCreate([
                        'key' => $key,
                        'value' => $value
                    ]);
                }
            }





            return response()->json(['status' => 200, 'message' => 'تمت العملية بنجاح']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'حدث خطأ أثناء العملية',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store or update a setting
     */
    private function storeOrUpdateSetting($key, $value)
    {
        ObjModel::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
