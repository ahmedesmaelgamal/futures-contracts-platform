<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvestorRequest as ObjRequest;
use App\Http\Requests\Vendor\StoreStockRequest;
use App\Models\Investor as ObjModel;
use App\Services\Vendor\InvestorService as ObjService;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function __construct(protected ObjService $objService) {}

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

    public function edit(ObjModel $investor)
    {
        return $this->objService->edit($investor);
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


    public function addStockForm($id)
    {
        return$this->objService->addStockForm($id);

    }

    public function storeStock(storeStockRequest $request)
    {


        $data = $request->validated();
        return $this->objService->storeStock($data);

    }

    public function getAvailableStock(Request $request)
    {
        return $this->objService->getAvailableStock($request);
    }


    public function InvestorStocksSummary($id)
    {
        return $this->objService->InvestorStocksSummary($id);
    }


    public function getCategoriesByInvestor($investor_id)
    {
        return $this->objService->getCategoriesByInvestor($investor_id);

    }

    public function getAllCategories()
    {

        return $this->objService->getAllCategories();

    }

}
