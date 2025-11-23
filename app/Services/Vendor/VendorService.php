<?php

namespace App\Services\Vendor;



use App\Models\Region;
use App\Models\Vendor as ObjModel;

use App\Models\VendorBranch;
use App\Services\Admin\CityService;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class VendorService extends BaseService
{
    protected string $folder = 'vendor/vendor';
    protected string $route = 'vendor.vendors';

    public function __construct(ObjModel $objModel, protected Region $region, protected BranchService $branchService, protected VendorBranch $vendorBranch, protected CityService $cityService)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getVendorDateTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    if ($obj->id == auth('vendor')->user()->id) {
                        $buttons = 'لأيمكن اتخاذ لي أجراء';
                    } else {


                                                                    if (auth('vendor')->user()->can('update_vendor')) {

                        $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>';
                    }
                                                                 if (auth('vendor')->user()->can('delete_vendor')) {

                        $buttons .= '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>';
                                                                 }


                    }


                    return $buttons;
                })->editcolumn('status', function ($obj) {

                    return $obj->id !== auth('vendor')->user()->id ? $this->statusDatatable($obj) : 'غير متاح';
                })->editcolumn('image', function ($obj) {

                    return $this->imageDataTable($obj->image);
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->editColumn('branches', function ($obj) {
                    $branchIds = $obj->branches->pluck('branch_id')->toArray();
                    $branches = $this->branchService->model->whereIn('id', $branchIds)->pluck('name')->toArray();
                    return implode(', ', $branches);
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            $auth = auth('vendor')->user();
            $branches = [];
            if ($auth->parent_id == null) {
                $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
            } else {
                $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
                $branches = $this->branchService->model->whereIn('id', $branchIds)->get();
            }
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => 'الموظفين',
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
            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->whereIn('id', $branchIds)->get();
        }

        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'cities' => $this->cityService->getAll(),
            'branches' => $branches,
            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

    public function store($data): JsonResponse
    {
        foreach ($data['branch_ids'] as $branch) {
            $branchName=$this->branchService->model->where('id', $branch)->first()->name;
            if ($branchName == 'الفرع الرئيسي' && count($data['branch_ids']) > 1) {
                return response()->json([
                    'status' => 405,
                    'message' => "لا يمكن إضافة فرع رئيسي مع أفرع أخر",
                ]);
            }
        }
        $allData = $data;
        unset($data['permissions'], $data['branch_ids']);
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Vendor');
        }

        $data['username'] = $this->generateUsername($data['name']);
        if (isset(auth()->user()->parent_id)) {
            $data['parent_id'] = auth()->user()->parent_id;
        } else {
            $data['parent_id'] = auth()->user()->id;
        }

        $data['password'] = Hash::make($data['password']);


        try {
            $permissions = Permission::whereIn('id', $allData['permissions'])->pluck('name')->toArray();
            $obj = $this->model->create($data);
            $obj->syncPermissions($permissions);

            //create vendor branch
            foreach ($allData['branch_ids'] as $branch_id) {
                $this->vendorBranch->create([
                    'vendor_id' => $obj->id,
                    'branch_id' => $branch_id,
                ]);

            }


            return $this->responseMsg();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ",
                'error' => $e->getMessage()
            ]);

        }
    }


    public function edit($id)
    {
        $obj = $this->getById($id);
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->whereIn('id', $branchIds)->get();
        }
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $id),
            'cities' => $this->cityService->getAll(),
            'branches' => $branches,

            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

    public function myProfile()
    {
        $vendor = auth()->guard('vendor')->user();
        return view($this->folder . '/profile', compact('vendor'));
    }//end fun

    public function update($data): JsonResponse
    {
        try {
            $allData = $data;
            unset($data['permissions'], $data['branch_ids']);
            $oldObj = $this->getById($data['id']);

            if (isset($data['image'])) {
                $data['image'] = $this->handleFile($data['image'], 'Vendor');

                if ($oldObj->image) {
                    $this->deleteFile($oldObj->image);
                }
            }

            if (isset(auth()->user()->parent_id)) {
                $data['parent_id'] = auth()->user()->parent_id;
            } else {
                $data['parent_id'] = auth()->user()->id;
            }

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Update model and get the instance
            $obj = $oldObj;
            $obj->update($data);

            // Sync permissions if provided
            if (isset($allData['permissions'])) {
                $permissions = Permission::whereIn('id', $allData['permissions'])->pluck('name')->toArray();
                $obj->syncPermissions($permissions);
            }

            // Delete existing branch relations and create new ones
            $this->vendorBranch->where('vendor_id', $obj->id)->delete();
            foreach ($allData['branch_ids'] as $branch_id) {
                $this->vendorBranch->create([
                    'vendor_id' => $obj->id,
                    'branch_id' => $branch_id,
                ]);
            }

            return $this->responseMsg();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ",
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getVendorsByModule($moduleId)
    {
        return \App\Models\Vendor::whereHas('vendor_modules', function ($query) use ($moduleId) {
            $query->where('module_id', $moduleId);
        })->get();
    }
}
