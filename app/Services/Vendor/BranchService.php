<?php

namespace App\Services\Vendor;

use App\Models\Branch as ObjModel;
use App\Models\City;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class BranchService extends BaseService
{
    protected string $folder = 'vendor/branch';
    protected string $route = 'branches';

    public function __construct(ObjModel $objModel, protected Vendor $vendor, protected VendorBranch $vendorBranch)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $auth = auth('vendor')->user();
            $parentId = $auth->parent_id ?? $auth->id;
            $child = $this->vendor->where('parent_id', $parentId)->pluck('id')->toArray();
            if ($auth->parent_id === null) {
                $child[] = $auth->id;
            } else {
                $child[] = $auth->parent_id;
            }


            $obj = $this->model->whereIn('vendor_id', $child)->get();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    if ($obj->is_main === 1) {
                        $buttons = '';
                        if (auth('vendor')->user()->can('update_branch')) {
                            $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                        }
                        return $buttons;

                    } else {
                        $buttons = '';
                        if (auth('vendor')->user()->can('update_branch')) {
                            $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                        }
                        if (auth('vendor')->user()->can('delete_branch')) {
                            $buttons .= '

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                        }
                    }

                    return $buttons;
                })->editColumn('status', function ($obj) {
                    return $obj->is_main === 1 ? 'غير متاح' : $this->statusDatatable($obj);
                })->editColumn('is_main', function ($obj) {
                    return $obj->is_main === 1 ? 'نعم' : 'لا';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => 'الفروع',
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): JsonResponse
    {

        try {
            $data['vendor_id'] = auth('vendor')->user()->id;
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        try {

            $oldObj = $this->getById($id);


            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }
}
