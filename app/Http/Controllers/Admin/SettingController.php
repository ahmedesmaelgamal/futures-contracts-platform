<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest as ObjRequest;
use App\Models\Setting as ObjModel;
use App\Services\Admin\SettingService as ObjService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {

        return $this->objService->index($request);
    }


    // public function edit(ObjModel $model)
    // {
    //     return $this->objService->edit($model);
    // }

    public function update(Request $request)
    {
        // dd($request->all());
        // $data = $request->validated();


        return $this->objService->update($request->all());
    }


}
