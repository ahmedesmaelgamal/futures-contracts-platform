<?php

namespace App\Services\Vendor;

use App\Services\BaseService;
use Carbon\Carbon;
use App\Models\ActivityLog as ObjModel;
use Yajra\DataTables\DataTables;
use App\Models\Vendor as VendorObj;

class ActivityLogService extends BaseService
{
    protected string $folder = 'vendor/activity_log';
    protected string $route = 'vendor.activity_logs';
    protected VendorObj $vendorObj;

    public function __construct(protected ObjModel $objModel, VendorObj $vendorObj)
    {
        $this->vendorObj = $vendorObj;
        parent::__construct($objModel);
    }


    public function index($request)
    {
        if ($request->ajax()) {
            $user = auth('vendor')->user();
            $parentId = $user->parent_id ?? $user->id;
            $objs = $this->model->where('userable_type', get_class($this->vendorObj))->where('userable_id', $parentId)->get();

            return DataTables::of($objs)
                ->addColumn('user', function ($obj) {
                    return $obj->userable ? $obj->userable->name : 'غير معروف';
                })
                ->addColumn('action', function ($obj) {
                    return $obj->action;
                })
                ->addColumn('ip_address', function ($obj) {
                    return $obj->ip_address ?? 'غير متوفر';
                })
                ->addColumn('created_at', function ($obj) {
                    Carbon::setLocale('ar');
                    return $obj->created_at->translatedFormat('j F Y الساعة g:i A');
                })
                ->addColumn('delete', function ($obj) {
                    $buttons = '';
                    if (auth('vendor')->user()->can('delete_activity_log')) {
                        $buttons .= '

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>


                    ';
                    }
                    return $buttons;

                })->rawColumns(['delete'])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "سجل الأنشطة",
                'route' => $this->route,
            ]);
        }
    }

}
