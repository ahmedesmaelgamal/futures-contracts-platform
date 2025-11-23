<?php
//
namespace App\Services\Vendor;

use App\Models\Plan;
use App\Models\PlanSubscription as ObjModel;
use App\Models\PlanDetail;
use App\Models\Setting;
use App\Services\BaseService;

class PlanService extends BaseService
{
    protected string $folder = 'vendor/plans';
    protected string $route = 'vendor.plans';

    public function __construct(ObjModel $objModel, protected PlanDetail $planDetail, protected Plan $plan, protected Setting $setting)
    {
        parent::__construct($objModel);
    }

    public function index()
    {
        $subscriptionWith0Price = $this->plan->where('price', 0)
            ->where('id', '!=', 1)
            ->get();
        $ifAuthHasSubcriptionWith0pricePlan =$this->model->where('vendor_id', VendorParentAuthData('id'))
            ->whereIn('plan_id', $subscriptionWith0Price->pluck('id'))
            ->where('status', 3)
            ->pluck('plan_id');
        return view($this->folder . '/index', [
            'createRoute' => route($this->route . '.create'),
            'route' => $this->route,
            'plans' => $this->plan->whereNotIn('id',$ifAuthHasSubcriptionWith0pricePlan)->get(),
            'planDetails' => $this->planDetail,
            'vendor_plans' => $this->model->where('vendor_id', auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id)->get(),
            'planSubscription' => $this->model->where('status', 1)->where('vendor_id', auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id)->where('plan_id', '!=', 1)->first(),
            'phones' => $this->setting->where('key', 'like', 'phone%')->get(),
            'bank_account' => $this->setting->where('key', 'iban')->first()? $this->setting->where('key', 'iban')->first(): null,
        ]);
    }


//
    public function store($data): \Illuminate\Http\JsonResponse
    {
        $vendor = auth('vendor')->user();
        $data['vendor_id'] = $vendor->parent_id == null ? $vendor->id : $vendor->parent_id;
        if ($vendor->plan_id != null) {
            $oldPlan = $this->model->where('vendor_id', $data['vendor_id'])->where('plan_id', '!=', 1)->where('status', 1)->first();
            $oldSubcriptionApplication = $this->model->where('vendor_id', $data['vendor_id'])->where('plan_id', $data['plan_id'])->where('status', 0)->first();

            if ($oldSubcriptionApplication) {
                return response()->json(['status' => 500, 'message' => "لقذ قمت بالفعل بتقديم طلب اشتراك في هذه الخطه يرجى الانتظار حتى يتم قبول الطلب من قبل الإدارة"]);
            }
        }
        $data['status'] = 0;

        $data['from'] = date('Y-m-d');
        $data['to'] = \Carbon\Carbon::now()->addDays($this->plan->where('id', $data['plan_id'])->value('period'))->format('Y-m-d');
        try {
            if (isset($data['payment_receipt'])) {
                $image = $this->handleFile($data['payment_receipt'], 'Plan_subscriptions');
            } else {
                return response()->json(['status' => 500, 'message' => "يرجى إرفاق إيصال الدفع"]);
            }
            $this->model->create([
                'vendor_id' => $data['vendor_id'],
                'plan_id' => $data['plan_id'],
                'status' => $data['status'],
                'from' => $data['from'],
                'to' => $data['to'],
                'payment_receipt' => $image,
            ]);

            return response()->json(['status' => 200, 'message' => "تم تقديم الطلب بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }
}
