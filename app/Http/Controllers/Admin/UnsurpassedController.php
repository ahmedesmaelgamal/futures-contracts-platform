<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UnsurpassedService as ObjService;
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

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }



    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }


}
