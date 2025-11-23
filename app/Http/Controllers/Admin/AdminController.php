<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest as ObjRequest;
use App\Models\Admin;
use App\Services\Admin\AdminService as ObjService;
use App\Traits\DreamsSmsTrait;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use DreamsSmsTrait;

    public function __construct(protected ObjService $objService){
    }


    public function sendOtp()
    {
        $response = $this->sendDreamsSms('9660567700065', 'رمز التحقق هو 1234');
        return response()->json($response);
    }
    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }

    public function myProfile()
    {
        return $this->objService->myProfile();
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $request)
    {
        $data = $request->validated();
        return $this->objService->store($data);
    }

    public function edit(Admin $admin)
    {
        return $this->objService->edit($admin);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data);
    }
    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request,'status');
    }



    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }

}//end class
