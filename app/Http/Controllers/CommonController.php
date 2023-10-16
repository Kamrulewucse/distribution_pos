<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\AccountHeadSubType;
use App\Models\AccountHeadType;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use App\Models\ProductModel;
use App\Models\ProductType;
use App\Models\PurchaseProduct;
use App\Models\PurchaseProductCategory;
use App\Models\PurchaseProductSubCategory;
use App\Models\SalesOrder;
use App\Models\SalesRepresentative;
use Illuminate\Http\Request;
use DB;

class CommonController extends Controller
{
    public function getBranch(Request $request) {
        $branches = Branch::where('bank_id', $request->bankId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($branches);
    }

    public function getBankAccount(Request $request) {
        $accounts = BankAccount::where('branch_id', $request->branchId)
            ->where('status', 1)
            ->orderBy('account_no')
            ->get()->toArray();

        return response()->json($accounts);
    }

    public function orderDetails(Request $request) {
        $order = SalesOrder::where('id', $request->orderId)->with('customer')->first()->toArray();

        return response()->json($order);
    }

    public function getAccountHeadType(Request $request) {
        $types = AccountHeadType::where('transaction_type', $request->type)
            ->where('status', 1)
            ->whereNotIn('id', [1, 2, 3, 4,5,6])
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($types);
    }

    public function getAccountHeadSubType(Request $request) {
        $subTypes = AccountHeadSubType::where('account_head_type_id', $request->typeId)
            ->where('status', 1)
            ->whereNotIn('id', [1, 2, 3, 4,5,6])
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($subTypes);
    }

    public function getSerialSuggestion(Request $request) {
        if ($request->has('term')) {
            return DB::table('purchase_order_purchase_product')->where('serial_no', 'like', '%'.$request->input('term').'%')->get();
        }
    }

    public function vatCorrection() {
        $orders = SalesOrder::where('vat_percentage', '!=', 0)->get();

        foreach ($orders as $order) {
            $total = $order->sub_total + $order->vat - $order->discount;
            $order->total = $total;

            if ($order->due == 0) {
                $order->paid = $total;
            } else {
                $order->due = $total;
            }

            $order->save();
        }
    }
    public function getSubCategory(Request $request) {
        $brand = ProductBrand::where('product_type_id', $request->productTypeID)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($brand);
    }
    public function getBrands(Request $request) {

        if ($request->srId != ''){

            $sr = SalesRepresentative::find($request->srId);

            $brands = ProductBrand::whereIn('id',json_decode($sr->brand))
                ->where('status', 1)
                ->orderBy('name')
                ->get()->toArray();
        }else{
            $brands = ProductBrand::where('product_type_id', $request->productTypeId)
                ->where('status', 1)
                ->orderBy('name')
                ->get()->toArray();
        }



        return response()->json($brands);
    }
    public function getProductType(Request $request) {
        $type = ProductType::where('id', $request->productTypeId)
            ->first()->toArray();
        return response()->json($type);
    }
    public function getProductColor(Request $request) {

        if ($request->productType == 3){
            $productModel = ProductModel::where('product_brand_id',$request->productBrandID)
                ->where('status', 1)
                ->orderBy('name')
                ->get()->toArray();

            return response()->json($productModel);
        }else{
            $brandColor = ProductModel::where('product_brand_id',$request->productBrandID)->where('status', 1)->first();

            $color = ProductColor::where('id', $brandColor->product_color_id)
                ->where('status', 1)
                ->orderBy('name')
                ->get()->toArray();

            return response()->json($color);
        }
    }
    public function getProductModel(Request $request) {

        $productModels = ProductModel::where('product_brand_id',$request->productBrandId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

            return response()->json($productModels);

    }
    public function getProduct(Request $request) {
        //$brandColor = ProductModel::where('id',$request->productColorID)->where('status', 1)->first();

        $product = ProductModel::where('product_color_id',$request->productColorID)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($product);
    }
    public function getProductCode(Request $request) {

        $product = PurchaseProduct::where('id', $request->productID)->first();

        return response()->json($product);
    }
    public function getProductUnitPrice(Request $request) {

        $inventory = ProductModel::where('id',$request->productModelId)->first();

        return response()->json(['inventory'=>$inventory,]);
    }

    public function cash(Request $request)
    {
        $cash = Cash::first();
        return view('cash.add', compact('cash'));
    }

    public function cashPost(Request $request)
    {
        $this->validate($request, [
            'opening_balance' => 'required'
        ]);
        $data = $request->all();
        $cash = Cash::first();
        if ($cash) {
            $old_amount = $request->opening_balance - $cash->opening_balance;
            $cash->opening_balance = $request->opening_balance;
            $cash->amount = $cash->amount + $old_amount;
            $cash->save();
        }else {
            $data['amount'] = $request->opening_balance;
            Cash::create($data);
        }
        return redirect()->back()->with('message','Cash added successfully done.');
    }
}
