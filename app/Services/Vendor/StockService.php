<?php

namespace App\Services\Vendor;

use App\Models\Investor;
use App\Models\Order;
use App\Models\Stock;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use App\Models\Stock as ObjModel;
use Illuminate\Http\JsonResponse;
use App\Services\Vendor\BranchService;
use App\Services\Vendor\OperationService;
use App\Services\Vendor\CategoryService;

class StockService extends BaseService
{
    protected string $folder = 'vendor/stock';
    protected string $route = 'stocks';

    public function __construct(
        ObjModel                  $objModel,
        protected CategoryService $categoryService,
        protected BranchService   $branchService,
        protected VendorBranch    $vendorBranch,
        protected Investor        $investor,
        protected OperationService $operationService,
        protected VendorService $vendorService,
        protected  Stock $stock,
        protected Order $order

    ) {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {

            $objs = $this->model
            ->whereIn('vendor_id', [auth('vendor')->user()->parent_id, auth('vendor')->user()->id]);

        if ($request->filled('investor_id')) {
            $objs = $objs->where('investor_id', $request->investor_id);
        }

        $objs = $objs->with(['branch', 'category', 'investor', 'operations'])->get();

            return DataTables::of($objs)
                ->addColumn('operation', function ($obj) {
                    return $obj->operations->where('stock_id', $obj->id)->first()->type == 1 ? "اضافه" : "انقاص";
                })
                ->addColumn('total_price', function ($obj) {
                    $price = $obj->operations->where('stock_id', $obj->id)->first()->type == 1 ? $obj->total_price_add : $obj->total_price_sub;
                    return $price;
                })
                ->addColumn('investor_national_id', function ($obj) {
                    return $obj->investor_id ? $obj->investor->national_id : "";
                }) ->editColumn('investor_id', function ($obj) {
                    return $obj->investor_id ? $obj->investor->name : "";
                })
                ->editColumn('branch_id', function ($obj) {
                    return $obj->branch_id ? $obj->branch->name : "";
                })
                ->editColumn('created_at', function ($obj) {
                    return $obj->created_at->translatedFormat('j F Y الساعة g:i A');
                })
                ->editColumn('category_id', function ($obj) {
                    return $obj->category_id ? $obj->category->name : "";
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendorService->model->where('parent_id', $parentId)->get();
            $vendors[] =  $this->vendorService->model->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'route' => $this->route,
                'categories' => $this->categoryService->model->where('vendor_id', VendorParentAuthData('id'))->get(),

                'investors' => $this->investor->whereIn('branch_id', $this->branchService->model->whereIn('vendor_id', $vendorIds)->pluck('id'))->get(),
            ]);
        }
    }



    public function filterTable($request)
    {


        if ($request->ajax()) {



            return DataTables::of([$request])

                ->addColumn('category', function ($request) {

                    return $request->category_id ? $this->categoryService->model->where('id', $request->category_id)->first()->name : "غير محدد";

                })->addColumn('added_quantity', function ($request) {

                    $stockIds =$this->stock->where('investor_id', $request->investor_id)->where('category_id', $request->category_id)->pluck('id');
                   $added= $this->operationService->model->whereIn('stock_id', $stockIds)->where('type', 1)->pluck('stock_id');
                    $addedQuantity = $this->stock->whereIn('id', $added)->sum('quantity');
                    return $addedQuantity;
                })->addColumn('subtracted_quantity', function ($request) {

                    $stockIds =$this->stock->where('investor_id', $request->investor_id)->where('category_id', $request->category_id)->pluck('id');
                   $subtracted= $this->operationService->model->whereIn('stock_id', $stockIds)->where('type', 0)->pluck('stock_id');
                    $subtractedQuantity = $this->stock->whereIn('id', $subtracted)->sum('quantity');
                    return $subtractedQuantity;
                })->addColumn('ordered_quantity', function ($request) {

                    $orderQuantity = $this->order->where('investor_id', $request->investor_id)->where('category_id', $request->category_id)->sum('quantity');
                    return $orderQuantity;


                })





                ->addColumn('remaining_quantity', function ($request) {
                    $stockIds = $this->stock->where('investor_id', $request->investor_id)
                        ->where('category_id', $request->category_id)
                        ->pluck('id');

                    // الكمية المضافة
                    $addedIds = $this->operationService->model->whereIn('stock_id', $stockIds)
                        ->where('type', 1)->pluck('stock_id');
                    $addedQuantity = $this->stock->whereIn('id', $addedIds)->sum('quantity');

                    // الكمية المنقصة
                    $subtractedIds = $this->operationService->model->whereIn('stock_id', $stockIds)
                        ->where('type', 0)->pluck('stock_id');
                    $subtractedQuantity = $this->stock->whereIn('id', $subtractedIds)->sum('quantity');

                    // الكمية المطلوبة
                    $orderedQuantity = $this->order->where('investor_id', $request->investor_id)
                        ->where('category_id', $request->category_id)
                        ->sum('quantity');

                    // الحساب الصحيح

//                    dd($addedQuantity , $subtractedQuantity , $orderedQuantity);
                    $remaining = $addedQuantity - ($subtractedQuantity + $orderedQuantity);
                    return $remaining;
                })



                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {

            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendorService->model->where('parent_id', $parentId)->get();
            $vendors[] =  $this->vendorService->model->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'route' => $this->route,
                'categories' => $this->categoryService->model->where('vendor_id', VendorParentAuthData('id'))->get(),
                'investors' => $this->investor->whereIn('branch_id', $this->branchService->model->whereIn('vendor_id', $vendorIds)->pluck('id'))->get(),
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
            'storeRoute' => route(name: "vendor.investors.storeStock"),
            'investors' => $this->investor->whereIn('branch_id', $branches->pluck('id'))->get(),
            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),
            'branches' => $branches,
        ]);
    }

    public function store($data): JsonResponse
    {
        try {
            $data['vendor_id'] = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;
            $data = $this->prepareStockData($data);
            $stockData = $data;
            unset($stockData['operation_type']);

            $obj = $this->createData($stockData);
            $this->operationService->model->create([
                'stock_id' => $obj->id,
                'type' => $data['operation_type'],
            ]);

            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    private function prepareStockData($data)
    {
        if ($data['operation'] == 1) {
            $data['price'] = ($data['total_price_add'] - ($data['vendor_commission'] + $data['investor_commission'] + $data['sell_diff'])) / $data['quantity'];
            $data['operation_type'] = 1;
        } else {
            $data['operation_type'] = 0;
        }
        unset($data['operation']);
        return $data;
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
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Stock');

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

    public function getBranches( $request)
    {
       $branches=$this->branchService->model->where('id', $this->investor->where('id', $request->investor_id)->first()->branch_id)->get();

        return response()->json([
            'branches' => $branches
        ]);
    }


}
