<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Http\Middleware\Custom\vendor;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Order as ObjModel;

//use App\Models\VendorModule;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class OrderService extends BaseService
{
    protected string $folder = 'admin/order';
    protected string $route = 'admin.orders';

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Region $region)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
//                ->addColumn('action', function ($obj) {
//                    $buttons = '
//
//                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
//                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
//                            <i class="fas fa-trash"></i>
//                        </button>
//                    ';
//                    return $buttons;
//                })
                ->addColumn('client_national_id', function ($obj) {
                    return $obj->client_id ? $obj->client->national_id : "";
                })->editColumn('client_id', function ($obj) {
                    return $obj->client_id ? $obj->client->name : "";
                })->editColumn('investor_id', function ($obj) {
                    return $obj->investor_id ? $obj->investor->name : "";
                })->editColumn('status', function ($obj) {
                    return $obj->status == 1 ? $status = "مكتمل" : $status = "جديد";
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
//                'createRoute' => route($this->route . '.create'),
                'bladeName' => "الطلبات",
                'route' => $this->route,
            ]);
        }
    }



}
