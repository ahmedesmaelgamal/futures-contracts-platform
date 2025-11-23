<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnsurpassedRequest as ObjRequest;
use App\Models\Unsurpassed as ObjModel;
use App\Services\Vendor\UnsurpassedService as ObjService;
use Illuminate\Http\Request;

class UnsurpassedController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function index(Request $request)
    {

        return $this->objService->index($request);
    }
    public function myUnsurpassed(Request $request)
    {

        return $this->objService->myUnsurpassed($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function addExcel()
    {
        return $this->objService->addExcel();
    }

    public function store(ObjRequest $request)
    {
        $data = $request->validated();
        return $this->objService->store($data);
    }
    public function storeExcel(Request $request)
    {
        return $this->objService->storeExcel($request);
    }
    public function edit(ObjModel $unsurpassed)
    {
        return $this->objService->edit($unsurpassed);
    }
    public function downloadExample()
    {
        return $this->objService->downloadExample();

    }
    public function show()
    {

    }


    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }



    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }


    public function pay($id){

        return $this->objService->pay($id);
    }
    public function checkByNationalId(Request $request)
    {
        return $this->objService->checkByNationalId($request);
    }

}
