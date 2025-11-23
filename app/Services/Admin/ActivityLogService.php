<?php

namespace App\Services\Admin;

use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\ActivityLog as ObjModel;
use Yajra\DataTables\DataTables;
use App\Models\Admin as AdminObj;

class ActivityLogService extends BaseService
{
    protected string $folder = 'admin/activity_log';
    protected string $route = 'admin.activity_logs';
    protected AdminObj $adminObj;

    public function __construct(protected ObjModel $objModel, AdminObj $adminObj)
    {
        $this->adminObj = $adminObj;
        parent::__construct($objModel);
    }


    public function index($request)
    {
        if ($request->ajax()) {
            $objs = $this->model->where('userable_type', get_class($this->adminObj))->get();

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
                    if (auth('admin')->user()->can('delete_activity_log')) {
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
