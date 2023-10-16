<?php

namespace App\Http\Controllers;

use App\Enumeration\Role;
use App\Models\ProductModel;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProduct;
use App\Models\SalePayment;
use App\Models\SalesOrder;
use App\Models\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $query = SalesOrder::query();
        if (auth()->user()->role == Role::$SR)
            $query->where('created_by',Auth::id());

        $todaySale = $query->whereDate('date', date('Y-m-d'))->sum('total');

      $query = SalesOrder::query();
        if (auth()->user()->role == Role::$SR)
            $query->where('created_by',Auth::id());

        $todayDue = $query->whereDate('date', date('Y-m-d'))->sum('due');

       $query = SalePayment::whereDate('date', date('Y-m-d'));
        if (auth()->user()->role == Role::$SR)
            $query->where('created_by',Auth::id());

        $todayDueCollection = $query->where('type', 1)
            ->where('received_type', 2)->sum('amount');
        $query = SalePayment::whereDate('date', date('Y-m-d'));
            if (auth()->user()->role == Role::$SR)
                $query->where('created_by',Auth::id());
        $todayCashSale = $query->where('type', 1)
            ->where('received_type', 1)->sum('amount');

        if (auth()->user()->role == Role::$SR){
            $todayExpense = 0;
        }else{
            $todayExpense = TransactionLog::whereDate('date', date('Y-m-d'))
                ->whereIn('transaction_type', [3, 2, 6])->sum('amount');
        }


         $query = SalesOrder::query();
        if (auth()->user()->role == Role::$SR)
            $query->where('created_by',Auth::id());

        $todaySaleReceipt = $query->with('customer')
            ->orderBy('created_at', 'desc')->paginate(10);

        $todaySaleReceipt->setPageName('sale_receipt');

        $todayPurchaseReceipt = PurchaseOrder::whereDate('date', date('Y-m-d'))
            ->with('supplier')
            ->orderBy('created_at', 'desc')->paginate(10);
        $todayPurchaseReceipt->setPageName('purchase_receipt');

        // Order Count By Month
        $startDate = [];
        $endDate = [];
        $saleAmountLabel = [];
        $saleAmount = [];

        for($i=11; $i >= 0; $i--) {
            $date = Carbon::now();
            $saleAmountLabel[] = $date->startOfMonth()->subMonths($i)->format('M, Y');
            $startDate[] = $date->format('Y-m-d');
            $endDate[] = $date->endOfMonth()->format('Y-m-d');
        }

        for($i=0; $i < 12; $i++) {
            $query = SalesOrder::query();
            if (auth()->user()->role == Role::$SR)
                $query->where('created_by',Auth::id());

            $saleAmount[] = $query->where('date', '>=', $startDate[$i])
                ->where('date', '<=', $endDate[$i])
                ->sum('total');
        }

        // Product Upload chart
        $orderCount = [];

        for($i=0; $i < 12; $i++) {
             $query = SalesOrder::query();
             if (auth()->user()->role == Role::$SR)
                $query->where('created_by',Auth::id());

            $orderCount[] = $query->where('date', '>=', $startDate[$i])
                ->where('date', '<=', $endDate[$i])
                ->count();
        }

        // Best Seller Products
        $bestSellingItemsSql = "SELECT product_models.id, count
                FROM product_models
                LEFT JOIN (SELECT product_model_id, SUM(quantity) count FROM product_model_sales_order GROUP BY product_model_id) t ON product_models.id = t.product_model_id
                WHERE product_models.status = 1
                ORDER BY count DESC
                LIMIT 10";

        $bestSellingItemsResult = DB::select($bestSellingItemsSql);
        $bestSellingItemsIds = [];

        foreach ($bestSellingItemsResult as $item)
            $bestSellingItemsIds[] = $item->id;

        $bestSellingItemsIdsString = implode(",", $bestSellingItemsIds);
        $bestSellingProductsQuery = ProductModel::query();
        $bestSellingProductsQuery->whereIn('id', $bestSellingItemsIds);

        if (count($bestSellingItemsIds) > 0)
            $bestSellingProductsQuery->orderByRaw('FIELD(id,'.$bestSellingItemsIdsString.')');
        $bestSellingProducts = $bestSellingProductsQuery->get();

        foreach ($bestSellingProducts as $product) {
            $product->count = DB::table('product_model_sales_order')
                ->where('product_model_id', $product->id)
                ->sum('quantity');
        }

        // Recently Added Product
        $recentlyProducts = ProductModel::take(10)->latest()->get();

        $data = [
            'todaySale' => $todaySale,
            'todayDue' => $todayDue,
            'todayDueCollection' => $todayDueCollection,
            'todayExpense' => $todayExpense,
            'todayCashSale' => $todayCashSale,
            'todaySaleReceipt' => $todaySaleReceipt,
            'todayPurchaseReceipt' => $todayPurchaseReceipt,
            'saleAmountLabel' => json_encode($saleAmountLabel),
            'saleAmount' => json_encode($saleAmount),
            'orderCount' => json_encode($orderCount),
            'bestSellingProducts' => $bestSellingProducts,
            'recentlyProducts' => $recentlyProducts
        ];

        return view('dashboard',$data);
    }

    public function paymentDetails()
    {
        return view('payment_details');
    }
}
