<?php

namespace App\Services\Vendor;

use App\Enums\OrderStatus;
use App\Exports\UnsurpassedExampleExport;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Unsurpassed as ObjModel;
use App\Models\Vendor;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Imports\UnsurpassedImport;
use App\Models\Investor;
use App\Models\InvestorWallet;
use App\Models\Order;
use App\Models\OrderStatus as ModelsOrderStatus;
use Maatwebsite\Excel\Facades\Excel;


class UnsurpassedService extends BaseService
{
    protected string $folder = 'vendor/unsurpassed';
    protected string $route = 'unsurpasseds';

    public function __construct(
        ObjModel $objModel,
        protected Excel $excel,
        protected UnsurpassedImport $unsurpassedImport,
        protected Client $client,
        protected Vendor $vendor,
        protected Branch $branch,
        protected Investor $investor,
        protected InvestorWallet $investorWallet,
        protected Order $order,
        protected ModelsOrderStatus $orderStatus
    ) {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {

            $unsurpassed = ObjModel::query()->select('*', DB::raw("'unsurpassed' as model_type"));

            $clients = $this->client->whereHas('orders', function ($query) {
                $query->whereHas('order_status', function ($q) {
                    $q->whereNotIn('status', [3]);
                });
            })->select('*', DB::raw("'client' as model_type"));

            $obj = $unsurpassed->unionAll($clients)->get();

            if ($request->filled('national_id')) {
                $obj = $obj->filter(function ($item) use ($request) {
                    return $item->national_id === $request->national_id;
                });
            }

            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $branch = $this->branch->where('id', $obj->office_phone)->first(); // i use office_phone because unionAll rename cols
                    if ($branch) {
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    } else {


                        $vendorId = null;
                    }

                    if ($obj->model_type === 'unsurpassed' && $obj->office_phone === VendorParentAuthData('phone')) {

                        $buttons = '';

                        if (auth()->user()->can('update_unsurpassed')) {
                            $buttons .= '
        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
            <i class="fa fa-edit"></i>
        </button>
    ';
                        }

                        if (auth()->user()->can('delete_unsurpassed')) {
                            $buttons .= '
        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
            <i class="fas fa-trash"></i>
        </button>
    ';
                        }

                    } else {
                        return '<h5 class="text-muted">لايمكنك اتخاد اي احراء</h5>';
                    }

                    return $buttons;
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->addColumn('office_phone', function ($obj) {


                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first(); // i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }


                    $phone = str_replace('+', '', $obj->model_type === 'client' ? $vendor->phone : $obj->office_phone);
                    return $phone;
                })->addColumn('office_name', function ($obj) {

                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first(); // i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }
                    return $obj->model_type === 'client' ? $vendor->name : $obj->office_name;
                })->addColumn('client_status', function ($obj) {

                    if ($obj->model_type === 'client') {
                        $order = $this->client->find($obj->id);
                        $orders = $order->orders ?? [];
                        $orderStatuses = [];
                        $orderDates = [];

                        foreach ($orders as $order) {
                            $orderStatuses[] = $order->order_status->status;
                            $orderDates[] = $order->order_status->date;
                        }

                        if (array_intersect([0, 1], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-warning'>متعثر</h5>";
                        } elseif (array_intersect([0, 1], $orderStatuses) && now()->lessThan(min($orderDates))) {
                            return "<h5 class='text-break'>لديه طلب قائم</h5>";
                        } elseif (array_intersect([3], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-primary'>غير منتظم في السداد</h5>";
                        } elseif (in_array(3, $orderStatuses) && !array_intersect([1, 2, 0], $orderStatuses)) {
                            return "<h5 class='text-success'>منتظم في السداد</h5>";
                        } else {
                            return "<h5 class='text-muted'>غير معلن </h5>";
                        }
                    }

                    return '<h5 class="text-muted">متعثر</h5>';
                })->addColumn('investor_name', function ($obj) {
                    if ($obj->model_type === 'unsurpassed') {
                        $investor = $this->investor->find($obj->investor_id);
                        return $investor ? $investor->name : 'لا يوجد';
                    }
                    return '';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendor->where('parent_id', $parentId)->get();
            $vendors[] =  $this->vendor->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            $obj = $this->investor->whereIn('Branch_id', $this->branch->whereIn('vendor_id', $vendorIds)->pluck('id'))->get();
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'addExcelRoute' => route($this->route . '.add.excel'),
                'bladeName' => "",
                'route' => $this->route,
                'obj' => $this->model->get(),
                'investors' => $obj,
            ]);
        }
    }
    public function myUnsurpassed($request)
    {
        if ($request->ajax()) {
            $investorId = $request->get('investor_id');
            $vendorId = auth('vendor')->user()->parent_id !== null ? auth('vendor')->user()->parent_id : auth('vendor')->user()->id;
            $unsurpassed = ObjModel::query()->where('office_phone', $this->vendor->where('id', $vendorId)->first()->phone)->select('*', DB::raw("'unsurpassed' as model_type"));

            if ($investorId) {
                $unsurpassed = $unsurpassed->where('investor_id', $investorId);
            }
            $clients = $this->client->whereHas('orders', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId)
                    ->whereHas('order_status', function ($q) {
                        $q->whereNotIn('status', [3]);
                    });
            })->select('*', DB::raw("'client' as model_type"));


            $obj = $unsurpassed->unionAll($clients)->get();


            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {

                    $branch = $this->branch->where('id', $obj->office_phone)->first(); // i use office_phone because unionAll rename cols
                    if ($branch) {
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    } else {


                        $vendorId = null;
                    }

                    if ($obj->model_type === 'unsurpassed' && $obj->office_phone === VendorParentAuthData('phone')) {

                        $buttons = '';

                        if (auth()->user()->can('update_unsurpassed')) {
                            $buttons .= '
        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
            <i class="fa fa-edit"></i>
        </button>
    ';
                        }

                        if (auth()->user()->can('delete_unsurpassed')) {
                            $buttons .= '
        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
            <i class="fas fa-trash"></i>
        </button>
    ';
                        }

                        if (auth()->user()->can('update_unsurpassed')) {
                            $buttons .= '
        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
            data-bs-target="#pay_modal" data-id="' . $obj->id . '" data-title="سداد مبلغ ' . $obj->debt . ' إلى ' . $obj->name . '">
            <i class="fas fa-money-bill-wave side-menu__icon"></i>
        </button>
    ';
                        }
                    } else {
                        return '<h5 class="text-muted">لايمكنك اتخاد اي احراء</h5>';
                    }

                    return $buttons;
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->addColumn('office_phone', function ($obj) {


                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first(); // i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }


                    $phone = str_replace('+', '', $obj->model_type === 'client' ? $vendor->phone : $obj->office_phone);
                    return $phone;
                })->addColumn('debt', function ($obj) {


                    if ($obj->model_type === 'client') {
                        $orders = $this->order->where('client_id', $obj->id)->pluck('id')->toArray();
                        $orderStatuses = $this->orderStatus->whereIn('order_id', $orders)->whereNot('status', OrderStatus::COMPLETELY_PAID->value)->get();
                        $total = 0;
                        foreach ($orderStatuses as $orderStatus) {
                            $order = $this->order->find($orderStatus->order_id);

                            $total = $total + ($order->required_to_pay - $orderStatus->paid);
                        }
                        return $total;
                    }

                    return $obj->debt;
                })->addColumn('office_name', function ($obj) {

                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first(); // i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }
                    return $obj->model_type === 'client' ? $vendor->name : $obj->office_name;
                })->addColumn('client_status', function ($obj) {

                    if ($obj->model_type === 'client') {
                        $order = $this->client->find($obj->id);
                        $orders = $order->orders ?? [];
                        $orderStatuses = [];
                        $orderDates = [];

                        foreach ($orders as $order) {
                            $orderStatuses[] = $order->order_status->status;
                            $orderDates[] = $order->order_status->date;
                        }

                        if (array_intersect([0, 1], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-warning'>متعثر</h5>";
                        } elseif (array_intersect([0, 1], $orderStatuses) && now()->lessThan(min($orderDates))) {
                            return "<h5 class='text-break'>لديه طلب قائم</h5>";
                        } elseif (array_intersect([3], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-primary'>غير منتظم في السداد</h5>";
                        } elseif (in_array(3, $orderStatuses) && !array_intersect([1, 2, 0], $orderStatuses)) {
                            return "<h5 class='text-success'>منتظم في السداد</h5>";
                        } else {
                            return "<h5 class='text-muted'>غير معلن </h5>";
                        }
                    }

                    return '<h5 class="text-muted">متعثر</h5>';
                })->addColumn('investor_name', function ($obj) {
                    if ($obj->model_type === 'unsurpassed') {
                        $investor = $this->investor->find($obj->investor_id);
                        return $investor ? $investor->name : 'لا يوجد';
                    }
                    return '';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendor->where('parent_id', $parentId)->get();
            $vendors[] =  $this->vendor->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            $obj = $this->investor->whereIn('Branch_id', $this->branch->whereIn('vendor_id', $vendorIds)->pluck('id'))->get();
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'addExcelRoute' => route($this->route . '.add.excel'),
                'bladeName' => "",
                'route' => $this->route,
                'obj' => $this->model->get(),
                'investors' => $obj,
            ]);
        }
    }




    public function create()
    {
        $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
        $vendors = $this->vendor->where('parent_id', $parentId)->get();
        $vendors[] =  $this->vendor->where('id', $parentId)->first();
        $vendorIds = $vendors->pluck('id');
        $obj = $this->investor->whereIn('Branch_id', $this->branch->whereIn('vendor_id', $vendorIds)->pluck('id'))->get();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'investors' => $obj,
        ]);
    }

    public function addExcel()
    {
        return view("{$this->folder}/parts/addExcel", [
            'storeExcelRoute' => route("{$this->route}.store.excel"),
        ]);
    }

    public function show() {}

    public function store($data): \Illuminate\Http\JsonResponse
    {

        try {
            $this->createData($data);
            // create  this unsurpassed in client table
            $auth = auth('vendor')->user();

            $vendorIds = $auth->parent_id === null ? [$auth->id] : [$auth->parent_id, $auth->id];
            $branches = $this->branch->apply()->whereIn('vendor_id', $vendorIds)->get();
            $branchIds = $branches->pluck('id')->toArray();

            $client = $this->client->where('national_id', $data['national_id'])
                ->whereIn('branch_id', $branchIds)
                ->first();

            if (!$client) {
                $client = $this->client->create([
                    'national_id' => $data['national_id'],
                    'name' => $data['name'],
                    'phone' => '+966' . $data['phone'],
                    'branch_id' => $this->branch->where('vendor_id', VendorParentAuthData('id'))->first()->id,
                ]);
            }


            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }

    public function storeExcel($data): \Illuminate\Http\JsonResponse
    {
        try {
            if (isset($data['excel_file'])) {
                $data->validate(['excel_file' => 'required|mimes:xlsx,xls,csv']);
                $file = $data->file('excel_file');

                Excel::import($this->unsurpassedImport, $file);

                return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح", 'reload' => true]);
            }
            return response()->json('error importing the excel file');
        } catch (\Exception $e) {
            return response()->json('error importing the excel file');
        }
    }

    public function edit($obj)
    {
        $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
        $vendors = $this->vendor->where('parent_id', $parentId)->get();
        $vendors[] =  $this->vendor->where('id', $parentId)->first();
        $vendorIds = $vendors->pluck('id');
        $investors = $this->investor->whereIn('Branch_id', $this->branch->whereIn('vendor_id', $vendorIds)->pluck('id'))->get();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'investors' => $investors,

        ]);
    }

    public function downloadExample()
    {
        return \Excel::download(new UnsurpassedExampleExport, 'unsurpassed_example.xlsx');
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Unsurpassed');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }


    public function pay($id)
    {
        $unsurpassed = $this->getById($id);
        if ($unsurpassed->status == 1) {
            return response()->json(['status' => 500, 'message' => "لم يتم العثور علي هذا المتعثر"]);
        }

        $investor = $this->investor->find($unsurpassed->investor_id);
        if (!$investor) {
            return response()->json(['status' => 500, 'message' => "لم يتم العثور علي هذا المستثمر"]);
        }
        $investor->balance += $unsurpassed->debt;
        $investor->save();

        // add record to investor wallet
        $this->investorWallet->create([
            'investor_id' => $unsurpassed->investor_id,
            'vendor_id' => auth('vendor')->user()->id,
            'amount' => $unsurpassed->debt,
            'type' => 0,
            'date' => now(),
            'note' => sprintf('تم سداد مستحقات المتعثر %s بقيمة %s', $unsurpassed->name, number_format($unsurpassed->debt))
        ]);

        $unsurpassed->delete();


        return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
    }

    // InvestorController.php
    public function checkByNationalId($request)
    {
        $request->validate([
            'national_id' => 'required|string|min:10|max:10',
        ]);

        $unsurpassed = $this->model->where('national_id', $request->national_id)->first();
        if (!$unsurpassed) {
            $unsurpassed = $this->client->where('national_id', $request->national_id)->first();
        }

        if ($unsurpassed) {
            return response()->json([
                'status' => 200,

                'unsurpassed' => [
                    'id' => $unsurpassed->id,
                    'name' => $unsurpassed->name,
                    'phone' => $unsurpassed->phone,
                ],
            ]);
        }

        return response()->json(['status' => 404, 'message' => 'لم يتم العثور على المتعثر']);
    }
}
