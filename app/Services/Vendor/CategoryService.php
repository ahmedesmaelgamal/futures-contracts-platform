<?php

namespace App\Services\Vendor;

use App\Models\Category as ObjModel;
use App\Models\StockDetail;
use App\Models\Vendor;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CategoryService extends BaseService
{
    protected string $folder = 'vendor/category';
    protected string $route = 'categories';

    public function __construct(ObjModel $objModel,protected StockDetail $stockDetail)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('vendor_id', auth('vendor')->user()->parent_id??auth('vendor')->user()->id)->get();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    if (auth('vendor')->user()->can('update_category')) {
                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (auth('vendor')->user()->can('delete_category')) {
                        $buttons .= '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    }
                    return $buttons;
                })->editColumn('status', function ($obj) {
                    return $this->statusDatatable($obj);
                })->editColumn('name', function ($obj) {

                    $addedStocks = $obj->stocks->flatMap(function ($stock) {
                        return $stock->operations->where('type', 1);
                    });

                    $removedStocks = $obj->stocks->flatMap(function ($stock) {
                        return $stock->operations->where('type', 0);
                    });

                    $add = $addedStocks->sum('stock.quantity');
                    $remove = $removedStocks->sum('stock.quantity');

                    $stockDetails = $this->stockDetail
                        ->whereIn('stock_id', $addedStocks->pluck('id'))
                        ->where('is_sold', 0)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $quantity = $add - $remove;

                    foreach ($stockDetails as $stockDetail) {
                        if ($quantity <= 0) {
                            break;
                        }

                        $used_quantity = min($stockDetail->quantity, $quantity);
                        $quantity -= $used_quantity;
                    }

                    $total = $add - $remove - $quantity;
                    return $obj->name . '  (' . $total . ')';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "",
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
        $data['vendor_id'] = $this->getVendorId();
        $data['status'] = 1;
        try {
            $this->createData($data);

            return response()->json(['status' => 200, 'message' => ('Data created successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }

    /**
     * Get the appropriate vendor ID based on authentication and hierarchy
     *
     * @return int
     */
    private function getVendorId(): int
    {
        $authenticatedVendorId = auth()->guard('vendor')->id();
        $vendor = Vendor::find($authenticatedVendorId);

        return ($vendor && $vendor->parent_id !== null)
            ? $vendor->parent_id
            : $authenticatedVendorId;
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
            $data['image'] = $this->handleFile($data['image'], 'Category');

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
}
