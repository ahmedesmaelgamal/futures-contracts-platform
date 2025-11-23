<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Http\Middleware\Custom\vendor;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Vendor as ObjModel;

//use App\Models\VendorModule;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class VendorService extends BaseService
{
    protected string $folder = 'admin/vendor';
    protected string $route = 'admin.vendors';

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Region $region)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('parent_id', null);
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    if (Auth::guard('admin')->user()->can('update_vendor')) {
                        $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                            </button>
                       ';
                    }


                    if (Auth::guard('admin')->user()->can('delete_vendor')) {
                        $buttons .= '

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>


                    ';
                        $buttons .= '
                            <a href="' . route($this->route . '.LoginAsVendor', $obj->id) . '" class="btn btn-pill btn-info-light">
                            <i class="fa fa-eye"></i>
                            </a>
                          ';
                    }
                    return $buttons;
                })
                ->editcolumn('status', function ($obj) {

                    return $this->statusDatatable($obj);
                })->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "المكاتب",
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'cities' => $this->cityService->getAll(),
            'vendors' => $this->model->all(),
            'regions' => $this->region->get(),
            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),

        ]);
    }

    public function store($data): JsonResponse
    {
        $allData = $data;

        if (isset($data['permissions'])) {
            unset($data['permissions']);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "المفتاح 'permissions' غير موجود في البيانات المرسلة.",
            ]);
        }

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Vendor');
        }

        $data['username'] = $this->generateUsername($data['name']);
        //check if phone is unique
        $phone = $this->model->where('phone', $data['phone'])->first();
        if ($phone) {
            return response()->json([
                'status' => 422,
                'message' => 'رقم الهاتف مستخدم من قبل',
            ]);
        }


        $data['password'] = Hash::make($data['password']);

        try {
            $permissions = Permission::whereIn('id', $allData['permissions'])->pluck('name')->toArray();
            $obj = $this->model->create($data);
            $obj->syncPermissions($permissions);

            // Create primary branch for the vendor with default settings
            $branch = Branch::create([
                'vendor_id' => $obj->id,
                'status' => 1,
                'is_main' => 1,
                'name' => 'الفرع الرئيسي'
            ]);

// Associate vendor with the created branch
            $vendorBranch = VendorBranch::create([
                'vendor_id' => $obj->id,
                'branch_id' => $branch->id,
            ]);

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
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'vendors' => $this->model->all(),
            'cities' => $this->cityService->getAll(),
            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

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

//            $data['phone'] = '+966' . $data['phone'];


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


            return $this->responseMsg();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ",
                'error' => $e->getMessage()
            ]);
        }
    }


    public  function  LoginAsVendor($id)
    {
        $obj = $this->getById($id);
        if ($obj) {
            Auth::guard('vendor')->login($obj);
            return redirect()->route('vendorHome');
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'المكتب غير موجود',
            ]);
        }

    }


}
