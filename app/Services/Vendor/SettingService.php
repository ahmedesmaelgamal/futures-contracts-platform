<?php

namespace App\Services\Vendor;

use App\Models\Setting as ObjModel;
use App\Services\Admin\CityService;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class SettingService extends BaseService
{
        protected string $folder = 'vendor/setting';
    protected string $route = 'vendorSetting';

    public function __construct(ObjModel $objModel, protected CityService $cityService,protected VendorService $vendor)
    {
        parent::__construct($objModel);
    }

    public function index()
    {


        $vendorSetting=$this->vendor->model->where('id',auth()->guard('vendor')->user()->id)
            ->orWhere('id',auth()->guard('vendor')->user()->parent_id)->first();



            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.store'),
                'bladeName' => 'الإعدادات',
                'route' => $this->route,
                'vendorSetting' => $vendorSetting,
                'cities' => $this->cityService->getAll(),
            ]);

    }


    public function UpdatePassword()
    {


        $vendorSetting=$this->vendor->model->where('id',auth()->guard('vendor')->user()->id)
            ->orWhere('id',auth()->guard('vendor')->user()->parent_id)->first();



            return view($this->folder . '/UpdatePassword', [
                'createRoute' => route($this->route . '.store'),
                'bladeName' => 'الإعدادات',
                'route' => $this->route,
                'vendorSetting' => $vendorSetting,
                'cities' => $this->cityService->getAll(),
            ]);

    }



    public function update($data): JsonResponse
    {
//        dd($data);

        unset($data['_token']);
        $vendorId = auth()->guard('vendor')->user()->id;
        $parentId = auth()->guard('vendor')->user()->parent_id;

        $obj = $this->vendor->model->where('id', $vendorId)
            ->orWhere('id', $parentId)
            ->first();

        // Save images in setting table
        if (isset($data['logo'])) {
            $data['logo'] = $this->handleFile($data['logo'], 'vendorSetting');
        }
        if (isset($data['loader'])) {
            $data['loader'] = $this->handleFile($data['loader'], 'vendorSetting');
        }
        if (isset($data['fav_icon'])) {
            $data['fav_icon'] = $this->handleFile($data['fav_icon'], 'vendorSetting');
        }

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'vendor');
        }

        // Remove unnecessary fields for settings
        $settingData = $data;
        unset(
            $data['image'], $data['name'], $data['phone'], $data['email'],
            $data['city_id'], $data['commercial_number'], $data['profit_ratio'],
            $data['is_profit_ratio_static'], $data['password'], $data['password_confirmation']
        );
        $settings = $this->model->whereIn('vendor_id', [$vendorId, $parentId])->get();

        try {
        foreach ($settings as $setting) {
            if (array_key_exists($setting->key, $data)) {
                $setting->value = $data[$setting->key];
                $setting->guard = 'vendor';
                $setting->vendor_id = $vendorId;
                $setting->save();

                unset($data[$setting->key]);
            }
        }

        foreach ($data as $key => $value) {
            $this->model->create([
                'vendor_id' => $vendorId,
                'key' => $key,
                'value' => $value,
                'guard' => 'vendor',
            ]);
        }





        if (isset($settingData['password']) && $settingData['password'] != null) {
                $settingData['password'] = Hash::make($settingData['password']);
            }else{
                $settingData['password'] = $obj->password;
            }

            // update vendor data
            $obj->update([
                'name' => $settingData['name'],
                'phone' => '+966'.$settingData['phone'],
                'image' => $settingData['image']??null,
                'email' => $settingData['email'],
                'city_id' =>$settingData['city_id'],
                'profit_ratio' => $settingData['profit_ratio'],
                'is_profit_ratio_static' => $settingData['is_profit_ratio_static'],
                'commercial_number' => $settingData['commercial_number'],
                'password' => $settingData['password'],
            ]);

            return $this->responseMsg();
        } catch (\Exception $e) {
            return $this->responseMsgError();
        }
    }


}
