<?php

namespace App\Services\Vendor;

use App\Models\Branch;
use App\Models\Investor;
use App\Models\InvestorWallet as ObjModel;
use App\Models\Vendor;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InvestorWalletService extends BaseService
{
    protected string $folder = 'vendor/investor_wallet';
    protected string $route = 'investor_wallets';

    public function __construct(ObjModel $objModel, protected Vendor $vendor, protected Branch $branch, protected Investor $investor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('vendor_id', VendorParentAuthData('id'));

            if ($request->filled('investor_id')) {
                $obj->where('investor_id', $request->investor_id);
            }

            if ($request->filled('type')) {
                $obj = $obj->where('type', $request->type);
            }
            $obj = $obj->orderBy('id', 'asc')->get();

            if ($request->filled('month')) {
                $obj = $obj->filter(function ($item) use ($request) {
                    return Carbon::parse($item->created_at)->month == $request->month;
                });
            }

            if ($request->filled('year')) {
                $obj = $obj->filter(function ($item) use ($request) {
                    return Carbon::parse($item->created_at)->year == $request->year;
                });
            }


            $totalAmount = $obj->where('type', 0)->sum('amount') -
                $obj->where('type', 1)->sum('amount');

            return DataTables::of($obj)
                ->editColumn('vendor_id', function ($obj) {
                    return $obj->vendor?->name;
                })->editColumn('investor_id', function ($obj) {
                    return $obj->investor?->name;
                })
                ->editColumn('type', function ($obj) {
                    return $obj->type == 0 ? "ايداع" : "سحب";
                })
                ->editColumn('date', function ($obj) {
                    Carbon::setLocale('ar');
                    return $obj->created_at->translatedFormat('j F Y الساعة g:i A');
                })->editColumn('note', function ($obj) {
                    return $obj->note ? $obj->note : "غير معلن";
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->with('total_amount', $totalAmount)
                ->make(true);
        } else {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendor->where('parent_id', $parentId)->get();
            $vendors[] = $this->vendor->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            $obj = $this->investor->whereIn('branch_id', $this->branch->whereIn('vendor_id', $vendorIds)->pluck('id'))->get();
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => " خزانه المستثمرين",
                'route' => $this->route,
                'investors' => $obj
            ]);
        }
    }

    public function create()
    {
        $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
        $vendors = $this->vendor->where('parent_id', $parentId)->get();
        $vendors[] = $this->vendor->where('id', $parentId)->first();
        $vendorIds = $vendors->pluck('id');
        $obj = $this->investor->whereIn('branch_id', $this->branch->whereIn('vendor_id', $vendorIds)->pluck('id'))->get();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'investors' => $obj,

        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        try {
            $vendorId = auth('vendor')->user()->id;
            $investorBalance = $this->investor->find($data['investor_id'])->balance;
            $amount = $data['amount'] ?? 0;
            $type = $data['type'] ?? null;

            // If type is 1, check if the vendor has enough balance
            if ($type == 1 && $investorBalance < $amount) {
                return response()->json(['status' => 405, 'mymessage' => "لا يوجد رصيد كافي في حساب هذا المستثمر."]);
            }

            \DB::beginTransaction();

            $data['vendor_id'] = $vendorId;
            $data['date'] = now();

            $this->createData($data);

            // Update investor balance
            $investor = $this->investor->find($data['investor_id']);
            if ($investor) {
                if ($type == 1) {
                    $investor->balance -= $amount;
                } else {
                    $investor->balance += $amount;
                }
                $investor->save();
            }

            \DB::commit();

            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }
}
