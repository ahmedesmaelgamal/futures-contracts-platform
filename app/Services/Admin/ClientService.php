<?php


namespace App\Services\Admin;

use App\Models\vendor;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Client as ObjModel;

use App\Services\BaseService;

use Yajra\DataTables\DataTables;

class ClientService extends BaseService
{
    protected string $folder = 'admin/client';
    protected string $route = 'admin.clients';

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Branch $branch, protected Region $region, protected Vendor $vendor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        $query = $this->model->newQuery()->with('branch');
        // Apply filters
        if ($request->office_id && $request->office_id != 'all') {
            // Get branches for this specific office
            $branchIds = $this->branch->where('vendor_id', $request->office_id)->pluck('id');

            if ($request->branch_id && $request->branch_id != 'all') {
                // Verify the branch belongs to the office before filtering
                if (in_array($request->branch_id, $branchIds->toArray())) {

                    $query->where('branch_id', $request->branch_id);
                } else {
                    // Branch doesn't belong to office - return empty
                    return DataTables::of(collect([]))->make(true);
                }
            } else {
                // Filter by all branches of this office
                $query->whereIn('branch_id', $branchIds);
            }
        } elseif ($request->office_id == 'all') {
            // All offices selected - handle branch filter if any
            if ($request->branch_id && $request->branch_id != 'all') {
                $query->where('branch_id', $request->branch_id);
            }
            // else no branch filter - show all
        } elseif ($request->branch_id) {
            // Only branch_id is specified (no office_id)
            $query->where('branch_id', $request->branch_id);
        }


        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('branch', function ($obj) {
                    return $obj->branch ? $obj->branch->name : "غير مرتبط بفرع";
                })
                ->addColumn('office', function ($obj) {
                    return $obj->branch->vendor->parent_id != null
                        ? $obj->branch->vendor->parent->name
                        : $obj->branch->vendor->name;
                })
                ->addColumn('name', function ($obj) {
                    return $obj->name;
                })
                ->addColumn('vendor', function ($obj) {
                    return $obj->vendor() ? $obj->vendor()->name : "غير مرتبط بموظف";
                })
                ->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "العملاء",
//                'branches' => $this->vendor=='all'?$this->model->pluck('branch_id')->unique():$this->branch->whereIn('id', $this->model->pluck('branch_id')->unique())->get(),
                'branches' => $this->vendor=='all'?$this->model->pluck('branch_id')->unique():$this->branch->get(),

                'offices' => $this->vendor->where('parent_id', null)->get(),
                'route' => $this->route,
            ]);
        }
    }
}
