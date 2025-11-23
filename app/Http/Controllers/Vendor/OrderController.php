<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest as ObjRequest;
use App\Http\Requests\Vendor\OrderStatusRequest;
use App\Services\Vendor\OrderService as ObjService;
use Illuminate\Http\Request;

class OrderController extends Controller
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

    public function editOrderStatus($id)
    {
        return $this->objService->editOrderStatus($id);
    }

    public function updateOrderStatus(OrderStatusRequest $request)
    {
        return $this->objService->updateOrderStatus($request);
    }





    public function destroy($id)
    {
        $this->objService->reverseStockDetails($id);
        return $this->objService->delete($id);
    }



    public function calculatePrices(Request $request){
        return $this->objService->calculatePrices($request);
    }

    public  function getInvestors(Request $request)
    {
        return $this->objService->getInvestors($request);

    }

    public  function getCategories(Request $request)
    {
        return $this->objService->getCategories($request);

    }
}
