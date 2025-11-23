<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanSubscriptionRequest as ObjRequest;
use App\Models\Plan;
use App\Models\PlanSubscription as ObjModel;
use App\Services\Admin\PlanSubscriptionService as ObjService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanSubscriptionController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $planSubscription)
    {
        return $this->objService->edit($planSubscription);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }
        public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request,'status');
    }

    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }


    public function getToDate(Request $request)
    {
        $plan = Plan::find($request->plan_id);

        if ($plan) {
            $from = $request->from ;

            $fromDate = $request->from ? Carbon::parse($request->from) : Carbon::now();

        $toDate = $fromDate->addDays($plan->period)->format('Y-m-d');

            return response()->json(['status' => 200, 'data' => $toDate]);
        }

        return response()->json(['status' => 400, 'message' => 'الخطة غير متاحة']);
    }

    public function rejectSubscription($id): \Illuminate\Http\JsonResponse
    {
        return $this->objService->rejectSubscription($id);

    }
    public function activateSubscription($id): \Illuminate\Http\JsonResponse
    {
        return $this->objService->activateSubscription($id);
    }
}
