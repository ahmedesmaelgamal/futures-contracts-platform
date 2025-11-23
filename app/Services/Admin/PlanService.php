<?php

namespace App\Services\Admin;

use App\Models\Plan as ObjModel;
use App\Models\Plan;
use App\Models\PlanDetail;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PlanService extends BaseService
{
    protected string $folder = 'admin/plan';
    protected string $route = 'Plans';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    if ($obj->id == 1) {
                        $buttons = '';
                        if (auth('admin')->user()->can('update_plan')) {

                            $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>

                    ';
                        }
                        return $buttons;
                    }
                    $buttons = '';
                    if (auth('admin')->user()->can('update_plan')) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>

                    ';
                    }
                    if (auth('admin')->user()->can('delete_plan')) {

                        $buttons .= '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    }
                    return $buttons;
                })->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('status', function ($obj) {
                    return $obj->id == 1 ? 'غير متاح' : $this->statusDatatable($obj);
                })->editColumn('description', function ($obj) {
                    return mb_strlen($obj->description, 'UTF-8') > 50 ? mb_substr($obj->description, 0, 50, 'UTF-8') . '...' : $obj->description;
                })->editColumn('discount', function ($obj) {
                    return $obj->discount . '%';
                })->editColumn('period', function ($obj) {
                    if ($obj->id!=1){

                        if ($obj->period <= 10) {
                            return $obj->period . ' ايام';
                        } else {
                            return $obj->period . ' يوم';
                        }
                    }else{
                        return 'غير محدود';
                    }

                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "الإشتراكات",
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

    public function store($validatedData)
    {
        try {
            if (isset($validatedData['image'])) {
                $image = $validatedData['image']->handleFile($validatedData['image'], 'Plan');
            }

            $plan = Plan::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'period' => $validatedData['period'],
                'discount' => $validatedData['discount'] ?? null,
                'description' => $validatedData['description'] ?? null,
                'image' => $image ?? null,
            ]);

            $this->createPlanDetails($plan, $validatedData['plans']);

            return response()->json([
                'status' => 200,
                'message' => "تمت العملية بنجاح"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ أثناء الحفظ",
                'error' => $e->getMessage()
            ]);
        }
    }

    private function createPlanDetails(Plan $plan, array $planDetails): void
    {
        foreach ($planDetails as $detail) {
            PlanDetail::create([
                'plan_id' => $plan->id,
                'key' => $detail['key'],
                'value' => isset($detail['is_unlimited']) && $detail['is_unlimited'] == 1 ? null : $detail['value'],
                'is_unlimited' => isset($detail['is_unlimited']) && $detail['is_unlimited'] == 1 ? 1 : 0,
            ]);
        }
    }


    public function edit($id)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $this->getById($id),
            'updateRoute' => route("{$this->route}.update", $id),
        ]);
    }

    public function update($request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return $this->notFoundResponse();
        }

        try {
            $data = $this->prepareUpdateData($request);
            $this->handleImageUpdate($request, $plan, $data);
            $this->updatePlanDetails($plan, $data);

            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    private function prepareUpdateData($request): array
    {
        return [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'period' => $request->input('period'),
            'discount' => $request->input('discount'),
            'description' => $request->input('description'),
            'plans' => $request->input('plans', [])
        ];
    }

    private function handleImageUpdate($request, Plan $plan, array &$data): void
    {
        if ($request->hasFile('image')) {
            if ($plan->image) {
                $this->deleteFile($plan->image);
            }
            $data['image'] = $this->handleFile($request->file('image'), 'Plan');
        } else {
            $data['image'] = $plan->image;
        }
    }

    private function updatePlanDetails(Plan $plan, array $data): void
    {
        $plan->update([
            'name' => $data['name'],
            'price' => $data['price'],
            'period' => $data['period'],
            'discount' => $data['discount'],
            'description' => $data['description'],
            'image' => $data['image'],
        ]);

        if (!empty($data['plans']) && is_array($data['plans'])) {
            $plan->details()->delete();
            $this->createPlanDetailsFromData($plan, $data['plans']);
        }
    }

    private function createPlanDetailsFromData(Plan $plan, array $planDetails): void
    {
        foreach ($planDetails as $detail) {
            PlanDetail::create([
                'plan_id' => $plan->id,
                'key' => $detail['key'] ?? null,
                'value' => $this->getPlanDetailValue($detail),
                'is_unlimited' => $this->isPlanDetailUnlimited($detail),
            ]);
        }
    }

    private function getPlanDetailValue(array $detail): ?string
    {
        return isset($detail['is_unlimited']) && $detail['is_unlimited'] == 1
            ? null
            : ($detail['value'] ?? null);
    }

    private function isPlanDetailUnlimited(array $detail): int
    {
        return isset($detail['is_unlimited']) ? 1 : 0;
    }

    private function notFoundResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 404,
            'message' => "البيانات غير موجودة"
        ]);
    }

    private function successResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 200,
            'message' => "تمت العمليه بنجاح"
        ]);
    }

    private function errorResponse(\Exception $e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 500,
            'message' => "حدث خطأ",
            'error' => $e->getMessage()
        ]);
    }
}
