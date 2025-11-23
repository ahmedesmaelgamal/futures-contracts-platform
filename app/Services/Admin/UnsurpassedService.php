<?php

namespace App\Services\Admin;

use App\Models\Unsurpassed as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class UnsurpassedService extends BaseService
{
    protected string $folder = 'admin/unsurpassed';
    protected string $route = 'admin.unsurpasseds';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->editColumn('office_phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addColumn('actions', function ($obj) {
                    $buttons = '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })

                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            // dd('test');
            return view($this->folder . '/index', [
                'bladeName' => "المتعثرين",
                'route' => $this->route,
                'obj' => $this->getAll(),
            ]);
        }
    }
}
