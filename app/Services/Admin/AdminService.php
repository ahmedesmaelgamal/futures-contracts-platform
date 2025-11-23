<?php

namespace App\Services\Admin;

use App\Enums\RoleEnum;
use App\Models\Admin as ObjModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use App\Services\BaseService;

class AdminService extends BaseService
{
    protected string $folder = 'admin/admin';
    protected string $route = 'admins';

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $admins = $this->getDataTable();
            return DataTables::of($admins)
                ->addColumn('action', function ($admins) {
                    $buttons = '';
                    if (auth('admin')->user()->can('update_admin')) {
                        if ($admins->id != 1 || auth()->guard('admin')->user()->id == 1) {
                            $buttons .= '
                            <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn" onclick="setTimeout(checkSelectAllStatus, 200)">
                            <i class="fa fa-edit"></i>
                            </button>
                            ';
                        }
                    }
                    if (auth('admin')->user()->can('delete_admin')) {

                        if (auth()->guard('admin')->user()->id != $admins->id && $admins->id != 1) {
                            $buttons .= '<button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $admins->id . '" data-title="' . $admins->name . '">
                        <i class="fas fa-trash"></i>
                        </button>';
                        }
                    }
                    return $buttons;
                })
                ->editColumn('phone', function ($admins) {
                    $phone = str_replace('+', '', $admins->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'title' => "المشرفين"
            ]);
        }
    }

    public
    function myProfile()
    {
        $admin = auth()->guard('admin')->user();
        return view($this->folder . '/profile', compact('admin'));
    }//end fun


    public
    function create()
    {
        $code = $this->generateCode();
        $roles = Role::all();
        return view($this->folder . '/parts/create', [
            'permissions' => Permission::where('guard_name', 'admin')
                ->get(),
            'code' => $code,
        ]);
    }

    public
    function store($data): \Illuminate\Http\JsonResponse
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
        $data['status'] = 1;
        $data['password'] = Hash::make($data['password']);

        $data['user_name'] = $this->generateUsername($data['name']);
        $permissions = Permission::whereIn('id', $allData['permissions'])->pluck('name')->toArray();
        $obj = $this->model->create($data);
        $obj->syncPermissions($permissions);
        if ($obj) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public
    function edit($admin)
    {
        return view($this->folder . '/parts/edit', [
            'permissions' => Permission::where('guard_name', 'admin')
                ->get(),
            'admin' => $admin
        ]);
    }

    public
    function update($data): JsonResponse
    {
        try {
            $allData = $data;
            if (isset($data['permissions'])) {
                unset($data['permissions']);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "المفتاح 'permissions' غير موجود في البيانات المرسلة.",
                ]);
            }
            $oldObj = $this->getById($data['id']);

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

    protected
    function generateCode(): string
    {
        do {
            $code = Str::random(11);
        } while ($this->firstWhere(['code' => $code]));

        return $code;
    }
}
