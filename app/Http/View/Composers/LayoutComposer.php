<?php

namespace App\Http\View\Composers;

use App\Enumeration\Role;
use App\Models\PurchaseInventory;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use DB;

class LayoutComposer
{
    public function __construct(){}
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {


        $query = SalesOrder::query();
        if (auth()->user()->role == Role::$SR)
            $query->where('created_by',Auth::id());

        $nextPayments = $query->where('next_payment', date('Y-m-d'))->get();
        $stocks = PurchaseInventory::with('productModel', 'warehouse')
            ->groupBy('product_model_id', 'warehouse_id')
            ->select(DB::raw('sum(`quantity`) as quantity, product_model_id, warehouse_id'))
            ->inRandomOrder()->limit(3)->get();

        $data = [
            'nextPayments' => $nextPayments,
            'stocks' => $stocks,
        ];

        $view->with('layoutData', $data);
    }
}
