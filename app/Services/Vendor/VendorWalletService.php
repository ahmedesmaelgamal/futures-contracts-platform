<?php

namespace App\Services\Vendor;

use App\Models\Vendor;
use App\Models\VendorWallet as ObjModel;
use App\Services\BaseService;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class VendorWalletService extends BaseService
{
    protected string $folder = 'vendor/vendor_wallet';
    protected string $route = 'vendor_wallets';

    public function __construct(ObjModel $objModel, protected Vendor $vendor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('vendor_id', VendorParentAuthData('id'))->get();
            if ($request->filled('type')) {
                $obj = $obj->where('type', $request->type);
            }

            if ($request->filled('month')) {
                $obj = $obj->filter(function ($item) use ($request) {
                    return Carbon::parse($item->date)->month == $request->month;
                });
            }

            if ($request->filled('year')) {
                $obj = $obj->filter(function ($item) use ($request) {
                    return Carbon::parse($item->date)->year == $request->year;
                });
            }
            $totalAmount = $obj->where('type', 0)->sum('amount')-
                $obj->where('type', 1)->sum('amount');
            return DataTables::of($obj)
                ->editColumn('auth_id', function ($obj) {
                    return $obj->whoDoOperation?->name;
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
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "خزانه المكتب",
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

    public function store($data): \Illuminate\Http\JsonResponse
    {
        try {

            $vendorId = VendorParentAuthData('id');
            $vendorBalance = VendorParentAuthData('balance');
            $amount = $data['amount'] ?? 0;
            $type = $data['type'] ?? null;

            // If type is 1, check if the vendor has enough balance
            if ($type == 1 && $vendorBalance < $amount) {
                return response()->json(['status' => 405, 'mymessage' => "لا يوجد رصيد كافي في حسابك."]);
            }

            \DB::beginTransaction();

            $data['vendor_id'] = $vendorId;
            $data['auth_id'] = auth('vendor')->user()->id;
            $data['date'] = now();

            $this->createData($data);

            // Update vendor balance
            $vendor = $this->vendor->find($vendorId);
            if ($vendor) {
                if ($type == 1) {//            $data['phone'] = '+966' . $data['phone'];

                    $vendor->balance -= $amount;
                } else {
                    $vendor->balance += $amount;
                }
                $vendor->save();
            }

            \DB::commit();

            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }
}
