<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreStockRequest as ObjRequest;
use App\Models\Stock as ObjModel;
use App\Services\Vendor\StockService as ObjService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function filterTable(Request $request)
    {

        return $this->objService->filterTable($request);
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

    public function edit(ObjModel $model)
    {
        return $this->objService->edit($model);
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
        public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request,'status');
    }

    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }

    public  function getBranches(Request $request)
    {
        return $this->objService->getBranches($request);

    }

    public function show()
    {



    }






}
