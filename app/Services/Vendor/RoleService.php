<?php

namespace App\Services\Vendor;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission as PermissionObj;
use Spatie\Permission\Models\Role as RoleObj;
use Yajra\DataTables\DataTables;

class RoleService extends BaseService
{
    protected string $folder = 'vendor/role';
    protected string $route = 'vendor.roles';
    protected RoleObj $roleObj;
    protected PermissionObj $permissionObj;

    public function __construct(PermissionObj $permissionObj, RoleObj $roleObj)
    {
        $this->roleObj = $roleObj;
        $this->permissionObj = $permissionObj;
        parent::__construct($roleObj);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $models = $this->model->where('guard_name', 'vendor')->get();
            return DataTables::of($models)
                ->addColumn('action', function ($models) {
                    $buttons = '';
                    $buttons .= '

                            <button type="button" data-id="' . $models->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                            </button>
                                                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $models->id . '" data-title="' . $models->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                       ';




                    return $buttons;
                })
                ->addColumn('name', function ($model) {
                    return $model->name;
                })
                ->addColumn('permissions', function ($models) {
                    return $models->permissions->count() > 0 ? '<span class="badge badge-success">' .
                        $models->permissions->count() . ' ' . "صلاحيات"
                        . '</span>' :
                        'لا توجد صلاحيات';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route
            ]);
        }
    }

    public function create()
    {
        $permissions = $this->permissionObj->all();
        $roles = $this->roleObj->all();
        return view($this->folder . '/parts/create', [
            'permissions' => $permissions,
            'storeRoute' => route($this->route . '.store'),
            'roles' => $roles
        ]);
    }


    public function store($data): JsonResponse
    {
        if (!is_array($data)) {
            return response()->json(['status' => 405, 'message' => 'Invalid data format']);
        }

        try {
            // Validate required fields
            if (!isset($data['name']) || empty($data['name'])) {
                return response()->json(['status' => 400, 'message' => 'Role name is required']);
            }

            if (!isset($data['guard_name']) || empty($data['guard_name'])) {
                return response()->json(['status' => 400, 'message' => 'Guard name is required']);
            }

            $roleData = [
                'name' => $data['name'],
                'guard_name' => $data['guard_name']
            ];

            $model = $this->createData($roleData);

            if (!$model) {
                return response()->json(['status' => 405, 'message' => 'Failed to create role']);
            }

            if (!empty($data['permissions']) && is_array($data['permissions'])) {
                $permissions = $this->permissionObj->whereIn('name', $data['permissions'])
                    ->where('guard_name', $data['guard_name'])
                    ->get();

                if ($permissions->isEmpty()) {
                    \Log::error('No permissions found for: ' . json_encode($data['permissions']) . ' with guard: ' . $data['guard_name']);
                    return response()->json(['status' => 405, 'message' => 'No permissions found']);
                }
                $model->syncPermissions($permissions);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Role created successfully',
                'role_id' => $model->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error assigning permissions: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    public function edit($role)
    {
        return view($this->folder . '/parts/edit', [
            'obj' => $role,
            'old_permissions' => $role->permissions()->pluck('name')->toArray(),
            'updateRoute' => route($this->route . '.update', $role->id),
        ]);
    }

    public function update($id, $data)
    {
        try {
            $model = $this->getById($id);

            if (!$model) {
                return response()->json(['status' => 404, 'message' => 'Role not found']);
            }

            $roleData = [
                'name' => $data['name'],
                'guard_name' => $data['guard_name']
            ];

            if (!$this->updateData($id, $roleData)) {
                return response()->json(['status' => 405, 'message' => 'Failed to update role']);
            }

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $permissions = $this->permissionObj->whereIn('name', $data['permissions'])
                    ->where('guard_name', $data['guard_name'])
                    ->get();

                if ($permissions->isEmpty()) {
                    \Log::error('No permissions found for: ' . json_encode($data['permissions']) . ' with guard: ' . $data['guard_name']);
                    return response()->json(['status' => 405, 'message' => 'No permissions found']);
                }

                $model->permissions()->sync($permissions->pluck('id')->toArray());
            }

            return response()->json(['status' => 200, 'message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Error updating role permissions: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

}
