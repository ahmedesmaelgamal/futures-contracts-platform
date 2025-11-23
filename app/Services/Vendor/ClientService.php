<?php

namespace App\Services\Vendor;

use App\Models\Branch;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use App\Models\Client as ObjModel;
use Illuminate\Support\Facades\Auth;

class ClientService extends BaseService
{
    protected string $folder = 'vendor/client';
    protected string $route = 'clients';

    public function __construct(ObjModel $objModel, protected BranchService $branchService, protected VendorBranch $vendorBranch)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->with('branch')->whereHas('branch', function ($query) {
                $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
                $vendors = Vendor::where('parent_id', $parentId)->get();
                $vendors[] = Vendor::where('id', $parentId)->first();
                $vendorIds = $vendors->pluck('id');
                $query->whereIn('vendor_id', $vendorIds);
            })->select('*');
            return DataTables::of($obj)
                ->editColumn('branch_id', function ($obj) {
                    return $obj->branch->name;
                })
                ->editcolumn('status', function ($obj) {

                    return $this->statusDatatable($obj);
                })

                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    if (Auth::guard('vendor')->user()->can("update_client")) {
                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (Auth::guard('vendor')->user()->can("delete_client")) {
                        $buttons .= '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    }
                    return $buttons;
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->addColumn('order_status', function ($obj) {

                    return $this->getOrderStatusForClient($obj);
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {

            $auth = auth('vendor')->user();
            $branches = [];
            if ($auth->parent_id == null) {
                $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])
                    ->get();
            } else {
                $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
                $branches = $this->branchService->model->whereIn('id', $branchIds)
                    ->get();
            }
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "العملاء",
                'route' => $this->route,
                'branches' => $branches,

            ]);
        }
    }

    public function create()
    {
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])
                ->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->whereIn('id', $branchIds)
                ->get();
        }
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'branches' => $branches,
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {

        try {
            $data['phone'] = '+966' . $data['phone'];
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {

        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])
                ->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->whereIn('id', $branchIds)
                ->get();
        }
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'branches' => $branches,
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Client');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }


    public function getUserByNationalId($data)
    {
        try {
//            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
//            $vendors = Vendor::where('parent_id', $parentId)->get();
//            $vendors[] = Vendor::where('id', $parentId)->first();
//            $vendorIds = $vendors->pluck('id');
//
//            $obj = $this->model->where('national_id', $data['national_id'])
//                ->whereIn('Branch_id', Branch::whereIn('vendor_id', $vendorIds)->pluck('id'))->first();


            $obj = $this->model->where('national_id', $data['national_id'])->first();


            if ($obj) {
                return response()->json([
                    'exists' => true,
                    'user' => [
                        'name' => $obj->name,
                        'phone' => $obj->phone,
                    ]
                ]);
            }

            return response()->json(['exists' => false]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }
}
