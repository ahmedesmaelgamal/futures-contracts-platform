<?php

namespace App\Services\Vendor;

use App\Models\OrderStatus;
use App\Models\Client;
use App\Models\Investor;
use App\Models\Order as ObjModel;
use App\Models\OrderInstallment;
use App\Models\Stock;
use App\Models\StockDetail;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class OrderService extends BaseService
{
    protected string $folder = 'vendor/order';
    protected string $route = 'orders';

    public function __construct(
        ObjModel                   $objModel,
        protected CategoryService  $categoryService,
        protected BranchService    $branchService,
        protected VendorBranch     $vendorBranch,
        protected Investor         $investor,
        protected StockService     $stockService,
        protected StockDetail      $stockDetail,
        private Client             $client,
        protected OrderInstallment $orderInstallment,
        protected Stock            $stock,
        protected Vendor           $vendor,
        protected OrderStatus      $orderStatus
    ) {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $parentId = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;

            $obj = $this->model->where('vendor_id', $parentId);

            if ($request->filled('investor_id')) {
                $obj = $obj->where('investor_id', $request->investor_id);
            }
            if ($request->filled('status')) {
                $obj = $obj->whereHas('order_status', function ($query) use ($request) {
                    $query->where('status', $request->status);
                });
            }

            if ($request->filled('month')) {
                $obj = $obj->whereMonth('date', $request->month);
            }

            if ($request->filled('year')) {
                $obj = $obj->whereYear('date', $request->year);
            }
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {

                    $buttons = '';
                    if ($obj->order_status->status != 3) {
                        if (auth('vendor')->user()->can('update_order')) {

                            $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-success-light editBtn">
                        <i class="fa fa-money-bill-wave side-menu__icon"></i>
                        </button>
                    ';
                        }
                        if (auth('vendor')->user()->can('delete_order')) {

                            $buttons .= '

                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
                        }
                    } else {
                        return "<h5 class='text-success'>مكتمل</h5>";
                    }


                    return $buttons;
                })->addColumn('client_national_id', function ($obj) {
                    return $obj->client_id ? $obj->client->national_id : "";
                })
                ->addColumn('client_status', function ($obj) {

                    return $this->getOrderStatusForClient($obj->client);
                })->addColumn('paid', function ($obj) {
                    return $obj->order_status->paid;
                })->editColumn('client_id', function ($obj) {
                    return $obj->client_id ? $obj->client->name : "";
                })->editColumn('date', function ($obj) {
                    return $obj->date ? Carbon::parse($obj->date)->subDays(3)->format('y-m-d') : "";
                })->editColumn('created_at', function ($obj) {
                    return $obj->created_at ? Carbon::parse($obj->created_at)->format('y-m-d') : "";
                })->editColumn('investor_id', function ($obj) {
                    return $obj->investor_id ? $obj->investor->name : "";
                })->addColumn('status', function ($obj) {
                    return $obj->date <= now() && $obj->order_status->status !== \App\Enums\OrderStatus::COMPLETELY_PAID->value ? "متعثر" : \App\Enums\OrderStatus::from($obj->order_status->status)->lang();
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendor->where('parent_id', $parentId)->get();
            $vendors[] = $this->vendor->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "الطلبات",
                'investors' => $this->investor->whereIn('branch_id', $this->branchService->model->whereIn('vendor_id', $vendorIds)->pluck('id'))->get(),

                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->apply()->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->apply()->whereIn('id', $branchIds)->get();
        }

        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),
            'investors' => $this->investor->whereIn('branch_id', $branches->pluck('id'))->get(),
            'branches' => $branches,
            'profit_ratio' => auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->profit_ratio
                : Vendor::where('id', auth('vendor')->user()->parent_id)->first()->profit_ratio,

            'is_profit_ratio_static' => auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->is_profit_ratio_static
                : Vendor::where('id', auth('vendor')->user()->parent_id)->first()->is_profit_ratio_static


        ]);
    }

    public function editOrderStatus($id)
    {
        $order = $this->getById($id);
        return view("{$this->folder}/parts/editOrderStatus", [
            'obj' => $order,
            'updateRoute' => route("vendor.orders.updateOrderStatus", parameters: $order->id),
        ]);
    }

    public function updateOrderStatus($request)
    {
        try {
            $order = $this->getById($request->id);

            if ($this->isGracePeriod($request)) {
                $this->applyGracePeriod($order, $request);
            } else {
                $this->updatePaymentStatus($order, $request);
                $this->addOrSubBalanceToInvestor($order['investor_id'], $request->paid, 0, "اضافة مبلغ لطلب رقم $order->order_number");
            }

            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }

    private function isGracePeriod($request): bool
    {
        return isset($request->is_graced) && $request->is_graced === 'on';
    }

    private function applyGracePeriod($order, $request): void
    {
        $order->order_status->update([
            'is_graced' => 1,
            'grace_period' => $request->grace_period,
            'grace_date' => Carbon::parse($order->date)->addDays($request->grace_period),
            'status' => 2,
        ]);
        $order->date = Carbon::parse($order->date)->addDays($request->grace_period);
    }

    private function updatePaymentStatus($order, $request): void
    {
        $order->order_status->increment('paid', $request->paid);


        $newStatus = $order->order_status->paid == $order->required_to_pay ? 3 : 1;
        $order->order_status->update(['status' => $newStatus]);
    }


    public function calculatePrices($request)
    {

        $stock = $this->stockService->model
            ->where('investor_id', $request->investor_id)
            ->where('branch_id', $request->branch_id)
            ->where('category_id', $request->category_id)
            ->whereHas('operations', function ($query) {
                $query->where('type', 1);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $stockDetails = $this->stockDetail
            ->whereIn('stock_id', $stock->pluck('id'))
            ->where('is_sold', 0)
            ->orderBy('created_at', direction: 'asc')
            ->get();

        $quantity = $request->quantity;
        $expected_price = 0;
        $Total_expected_commission = 0;
        $sell_diff = 0;
        $total_vendor_commission = 0;
        $total_investor_commission = 0;
        foreach ($stockDetails as $stockDetail) {
            if ($quantity <= 0) {
                break;
            }

            $used_quantity = min($stockDetail->quantity, $quantity);
            $quantity -= $used_quantity;

            $expected_price += $stockDetail->price;
            $total_vendor_commission += $stockDetail->vendor_commission;
            $total_investor_commission += $stockDetail->investor_commission;
            $Total_expected_commission += $stockDetail->vendor_commission + $stockDetail->investor_commission;
            $sell_diff += $stockDetail->sell_diff;
        }


        $available_quantity = $request->quantity - $quantity; // الكمية الحقيقية المتوفرة اللي تم استخدامها

        return response()->json([
            'expected_price' => $expected_price,
            'Total_expected_commission' => $Total_expected_commission,
            'sell_diff' => $sell_diff,
            'available_quantity' => $available_quantity,
            'vendor_commission' => $total_vendor_commission ?? 0,
            'investor_commission' => $total_investor_commission ?? 0

        ]);
    }

    public function store($data): JsonResponse
    {
        try {


            $vendorCommission = $data['vendor_commission'];

            $this->addOrSubBalanceToInvestor($data['investor_id'], $data['investor_commission'], 0, "اضافة عموله الطلب");

            // unset($data['investor_commission'], $data['vendor_commission']);


            // check if client exists
            $data['client_id'] = $this->checkClient($data);
            $this->setDefaultInstallmentValues($data);


            $stockDetails = $this->getAvailableStockDetails($data);
            $seletedIds = $this->markStockAsSold($stockDetails, $data['quantity']);

            $order = $this->storeOrder($data);

            $this->storeOrderDetails($order, $seletedIds);

            $this->storeOrderStatus($order);

            $this->addOrSubBalanceToVendor($vendorCommission, 0, "اضافة عموله المكتب ", $order->order_number);


            if (isset($data['is_installment']) && $data['is_installment'] === 'on' && $data['installment_number'] > 0) {
                $this->createInstallments($order, $data);
            }
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }


    public function storeOrderStatus($order)
    {
        return $order->order_status()->create([
            'order_id' => $order->id,
            'date' => $order->date,
            'vendor_id' => auth('vendor')->user()->id,
        ]);
    }


    public function storeOrderDetails($order, $seletedIds)
    {
        foreach ($seletedIds as $id) {
            $order->details()->create([
                'stock_detail_id' => $id,
                'order_id' => $order->id,

            ]);
        }
    }


    public function checkClient($data)
    {
        $auth = auth('vendor')->user();

        $vendorIds = $auth->parent_id === null ? [$auth->id] : [$auth->parent_id, $auth->id];
        $branches = $this->branchService->model->apply()->whereIn('vendor_id', $vendorIds)->get();
        $branchIds = $branches->pluck('id')->toArray();

        $client = $this->client->where('national_id', $data['national_id'])
            ->whereIn('branch_id', $branchIds)
            ->first();

        if (!$client) {
            $client = $this->client->create([
                'national_id' => $data['national_id'],
                'name' => $data['name'],
                'phone' => '+966' . $data['phone'],
                'branch_id' => $data['branch_id'],
            ]);
        }

        return $client->id;
    }


    private function setDefaultInstallmentValues(&$data): void
    {
        $data['is_installment'] = $data['is_installment'] ?? 'off';
        $data['installment_number'] = $data['installment_number'] ?? 0;
    }


    private function getAvailableStockDetails($data)
    {
        $stock = $this->stockService->model
            ->where('investor_id', $data['investor_id'])
            ->where('branch_id', $data['branch_id'])
            ->where('category_id', $data['category_id'])
            ->whereHas('operations', fn($q) => $q->where('type', 1))
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->stockDetail
            ->whereIn('stock_id', $stock->pluck('id'))
            ->where('is_sold', 0)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    private function markStockAsSold($stockDetails, $quantity): array
    {
        $updatedIds = [];
        foreach ($stockDetails as $stockDetail) {
            if ($quantity <= 0) break;

            $stockDetail->update(['is_sold' => 1]);
            $updatedIds[] = $stockDetail->id;
            $quantity -= $stockDetail->quantity;
        }
        return $updatedIds;
    }


    private function storeOrder(&$data)
    {
        unset(
            $data['name'],
            $data['phone'],
            $data['national_id'],
            $data['is_installment'],
            $data['installment_number']
        );

        $data['date'] = Carbon::parse($data['date'])->addDays(3);
        $data['order_number'] = random_int(1000000, 9999999);
        $data['vendor_id'] = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;

        return $this->createData($data);
    }

    private function createInstallments($order, $data): void
    {
        $installmentNumber = $data['installment_number'];
        $installmentAmount = $data['required_to_pay'] / $installmentNumber;

        $startDate = Carbon::now();
        $endDate = Carbon::parse($data['date']);
        $totalDays = $startDate->diffInDays($endDate);
        $period = intval($totalDays / $installmentNumber);

        for ($i = 1; $i <= $installmentNumber; $i++) {
            $this->orderInstallment->create([
                'order_id' => $order->id,
                'installment' => $installmentAmount,
                'installment_date' => $startDate->copy()->addDays($i * $period),
            ]);
        }
    }


    public function reverseStockDetails($id)
    {
        $order = $this->getById($id);
        $orderStatus = $this->orderStatus->where('order_id', $order->id)->first();
        if ($orderStatus->paid > 0) {


            $this->addOrSubBalanceToInvestor($order->investor_id, $orderStatus->paid, 1, "بسبب الغاء طلب $order->order_number. تم انقاص ");
        }


        $stockDetailIds = $order->details()->where('order_id', $id)->pluck('stock_detail_id')->toArray();
        $this->updateStockDetail($stockDetailIds);
    }

    public function updateStockDetail($stockDetailIds)
    {
        $stockDetail = $this->stockDetail->whereIn('id', $stockDetailIds)->get();
        foreach ($stockDetail as $detail) {
            $detail->update([
                'is_sold' => 0,
            ]);
        }
    }


    public function getInvestors($request)
    {
        $branchId = $request->branch_id;
        $investors = $this->investor->where('branch_id', $branchId)->get(['id', 'name']);

        return response()->json([
            'investors' => $investors
        ]);
    }

    public function getCategories($request)
    {
        $investorId = $request->investor_id;

        $catIds = $this->stockService->model
            ->where('investor_id', $investorId)
            ->pluck('category_id');

        $categories = $this->categoryService->model->whereIn('id', $catIds)->get();

        $result = [];

        foreach ($categories as $category) {
            $add = $category->stocks
                ->where('investor_id', $investorId)
                ->flatMap(function ($stock) {
                    return $stock->operations->where('type', 1);
                });
            $addedStocks = $add->sum(function ($op) {
                return $op->stock->quantity ?? 0;
            });

            //            $remove = $category->stocks
            //                ->where('investor_id', $investorId)
            //                ->flatMap(function ($stock) {
            //                    return $stock->operations->where('type', 0);
            //                });
            //            $removedStocks = $remove->sum(function ($op) {
            //                return $op->stock->quantity ?? 0;
            //            });

            $sold = $this->stockDetail
                ->whereIn('stock_id', $add->pluck('id'))
                ->where('is_sold', 1)
                ->orderBy('created_at', 'asc')
                ->count();


            //            dd($addedStocks, $removedStocks, $sold);
            $total = $addedStocks - $sold;

            $result[] = [
                'id' => $category->id,
                'name' => $category->name . ' (' . $total . ')',
            ];
        }


        return response()->json([
            'categories' => $result
        ]);
    }
}
