<?php

namespace App\Services\Vendor;

use App\Models\Client;
use App\Models\Investor;
use App\Models\Order;
use App\Models\Unsurpassed;
use App\Models\Vendor as ObjModel;
use App\Models\Branch;
use App\Models\Category;
use App\Models\OrderStatus;
use App\Models\PlanSubscription;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Carbon\Carbon;

class HomeService extends BaseService
{



    public function __construct(
        protected ObjModel $objModel,
        protected PlanSubscription $planSubscription,
        protected Order $order,
        protected Unsurpassed $unsurpassed,
        protected Client $client,
        protected Branch $branch,
        protected Vendor $vendor,
        protected Investor $investor,
        protected VendorBranch $vendorBranch,
        protected Category $category,
        protected OrderStatus $orderStatus
    ) {
        parent::__construct($objModel);
    }
    public function index($request = null)
    {
        $parentId = VendorParentAuthData('id');
        $selectedYear = $request->year ?? 'all';
        $selectedMonth = $request->month ?? 'all';

        $subVendors = $this->getSubVendors($parentId, $selectedYear, $selectedMonth);
        $branchIds = $this->getBranchIds($subVendors);

        $clients = $this->getClients($branchIds, $selectedYear, $selectedMonth);
        $branches = $this->getBranches($branchIds, $selectedYear, $selectedMonth);
        $investors = $this->getInvestors($branchIds, $selectedYear, $selectedMonth);
        $orders = $this->getOrders($parentId, $selectedYear, $selectedMonth);
        $categories = $this->getCategories($parentId, $selectedYear, $selectedMonth);
        $unsurpassed = $this->getUnsurpassed(VendorParentAuthData('phone'), $selectedYear, $selectedMonth);

        // $investor_commission = $orders->sum('investor_commission');
        // $vendor_commission = $orders->sum('vendor_commission');
        // $total_commission = $investor_commission + $vendor_commission;

        $total_required_to_pay = $orders->sum('required_to_pay');
        $total_paid = $this->orderStatus->whereIn('order_id', $orders->pluck('id'))->sum('paid');
        $total_unsurpassed_to_pay = $orders->where('date', '>', Carbon::parse(now())->addDays(3)->format('Y-m-d'))->sum('required_to_pay');
        $total_unpaid = $total_required_to_pay - $total_paid - $total_unsurpassed_to_pay;

        $years = range(2025, date('Y'));
        $months = ['1' => 'يناير', '2' => 'فبراير', '3' => 'مارس', '4' => 'أبريل', '5' => 'مايو', '6' => 'يونيو', '7' => 'يوليو', '8' => 'أغسطس', '9' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'];

        return view('vendor/index', [
            'clients' => $clients->count(),
            'branches' => $branches->count(),
            'investors' => $investors->count(),
            'subVendors' => $subVendors->count(),
            'unsurpassed' => $unsurpassed->count(),
            'orders' => $orders->count(),
            // 'investor_commission' => $investor_commission,// not needed
            // 'vendor_commission' => $vendor_commission,// not needed
            // 'total_commission' => $total_commission,
            'total_vendor_balance'=>VendorParentAuthData('balance'),
            'total_investor_balance'=>$investors->sum('balance'),
            'categories' => $categories->count(),
            'total_required_to_pay' => $total_required_to_pay,
            'total_paid' => $total_paid,
            'total_unsurpassed_to_pay' => $total_unsurpassed_to_pay,
            'total_unpaid' => $total_unpaid,
            'years' => $years,
            'months' => $months,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,




        ]);
    }
    private function getSubVendors($parentId, $year, $month)
    {
        $query = $this->vendor->where('parent_id', $parentId);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);

        $vendors = $query->get();
        $vendors->push($this->vendor->find($parentId));
        return $vendors;
    }

    private function getBranchIds($vendors)
    {
        return $this->vendorBranch
            ->whereIn('vendor_id', $vendors->pluck('id'))
            ->pluck('branch_id');
    }

    private function getClients($branchIds, $year, $month)
    {
        $query = $this->client->whereIn('branch_id', $branchIds);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);
        return $query->get();
    }

    private function getBranches($branchIds, $year, $month)
    {
        $query = $this->branch->whereIn('id', $branchIds);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);
        return $query->get()->unique('id');
    }

    private function getInvestors($branchIds, $year, $month)
    {
        $query = $this->investor->whereIn('branch_id', $branchIds);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);
        return $query->get();
    }

    private function getOrders($vendorId, $year, $month)
    {
        $query = $this->order->where('vendor_id', $vendorId);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);
        return $query->get();
    }

    private function getCategories($vendorId, $year, $month)
    {
        $query = $this->category->where('vendor_id', $vendorId);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);
        return $query->get();
    }

    private function getUnsurpassed($phone, $year, $month)
    {
        $query = $this->unsurpassed->where('office_phone', $phone);
        if ($year != 'all') $query->whereYear('created_at', $year);
        if ($month != 'all') $query->whereMonth('created_at', $month);
        return $query->get();
    }
}
