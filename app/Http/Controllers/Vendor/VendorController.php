<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorRequest as ObjRequest;
use App\Services\Vendor\VendorService as ObjService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
        $data = $data->validated();
        return $this->objService->store($data);
    }



    public function edit($id)
    {
        return $this->objService->edit($id);
    }

    public function update(ObjRequest $request)
    {
        $data = $request->validated();
        return $this->objService->update($data);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }

    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request, 'status');
    }


    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }
    public function myProfile()
    {
        return $this->objService->myProfile();
    }










//     public function updateProfile(ObjRequestProfile $request)
//     {
// //        dd($request->all());

//         $data = $request->validated();
//         return $this->objService->updateProfile($data);
//     }

//     public function editProfile()
//     {
//         return $this->objService->editProfile();
//     }
//     public function editProfileImage()
//     {
//         return $this->objService->editProfileImage();
//     }
//     public function updateProfileImage(ObjRequestProfileImage $request)
//     {
// //        dd($request->all());

//         $data = $request->validated();
//         return $this->objService->updateProfileImage($data);
//     }
}
