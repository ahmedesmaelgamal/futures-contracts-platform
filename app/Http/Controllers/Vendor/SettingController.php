<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\SettingRequest;
use Illuminate\Http\Request;
use App\Services\Vendor\SettingService as ObjService;

class SettingController extends Controller
{

    public function __construct(protected  ObjService $service)
    {


    }


    public function index()
    {
        return $this->service->index();

    }

    public function UpdatePassword()
    {
        return $this->service->UpdatePassword();

    }

    public function update(SettingRequest  $request)
    {

        return $this->service->update($request->all());

    }

}
