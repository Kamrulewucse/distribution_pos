<?php

namespace App\Http\Controllers;

use App\Models\ProductColor;
use App\Models\ProductModel;
use App\Models\ProductType;
use App\Models\PurchaseInventory;
use App\Models\PurchaseInventoryLog;
use App\Models\PurchaseOrderPurchaseProduct;
use App\Models\UpdatePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductModelController extends Controller
{
    public function index() {
        $products = ProductModel::all();
        return view('purchase.product.all', compact('products'));
    }

    public function add() {
        $productTypes =ProductType::where('status',1)->get();
        return view('purchase.product.add',compact('productTypes'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_brand' => 'required',
            'product_type' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'model_discount' => 'required|numeric|min:0',
            'code' => 'required|unique:product_models,code|max:255',
            'status' => 'required'
        ]);

        $productModel = new ProductModel();
        $productModel->name = $request->name;
        $productModel->product_type_id = $request->product_type;
        $productModel->product_brand_id = $request->product_brand;
        $productModel->purchase_price = $request->purchase_price;
        $productModel->selling_price = $request->selling_price;
        $productModel->model_discount = $request->model_discount;
        $productModel->code = $request->code ?? '';
        $productModel->status = $request->status;
        $productModel->save();

        return redirect()->route('purchase_product')->with('message', 'Product Model add successfully.');
    }

    public function edit(ProductModel $productModel) {
        $productTypes =ProductType::where('status',1)->get();
        return view('purchase.product.edit', compact( 'productModel','productTypes'));
    }

    public function editPost(ProductModel $productModel, Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'product_brand' => 'required',
            'product_type' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'model_discount' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

        $productModel->name = $request->name;
        $productModel->product_type_id = $request->product_type;
        $productModel->product_brand_id = $request->product_brand;
        $productModel->purchase_price = $request->purchase_price;
        $productModel->selling_price = $request->selling_price;
        $productModel->model_discount = $request->model_discount;
        $productModel->code = $request->code ?? '';
        $productModel->status = $request->status;
        $productModel->save();

        return redirect()->route('purchase_product')->with('message', 'Product Model Edit Successfully.');
    }

    public function modelPriceUpdate(Request $request){

        $rules = [
            'product_model_id' => 'required',
            'purchase_price' => 'required',
            'date' => 'required',
            'selling_price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
       $productModel = ProductModel::where('id',$request->product_model_id)->first();
       $count_model = PurchaseInventory::where('product_model_id', $request->product_model_id)
            ->where('quantity', '>', 0)->count();

        $updatePrice = new UpdatePrice();
        $updatePrice->product_model_id = $productModel->id;
        $updatePrice->date = $request->date;
        $updatePrice->old_purchase_price = $productModel->purchase_price;
        $updatePrice->old_selling_price = $productModel->selling_price;
        $updatePrice->updated_purchase_price = $request->purchase_price;
        $updatePrice->updated_selling_price = $request->selling_price;
        $updatePrice->total_quantity = $count_model ?? '';
        $updatePrice->reduce_price = $productModel->purchase_price-$request->purchase_price;
        $updatePrice->total_price = $updatePrice->reduce_price * $updatePrice->total_quantity;
        $updatePrice->save();


        ProductModel::where('id', $request->product_model_id)->update([
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
        ]);

        PurchaseOrderPurchaseProduct::where('product_model_id', $request->product_model_id)->update([
            'unit_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
        ]);
        PurchaseInventory::where('product_model_id', $request->product_model_id)
            ->where('quantity', '>', 0)->update([
            'last_unit_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
        ]);
        PurchaseInventoryLog::where('product_model_id', $request->product_model_id)
            ->where('sales_order_id', null)->update([
            'unit_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
        ]);

        return response()->json(['success' => true, 'message' => 'Update Price Successfully !.']);

    }

    public function PriceUpdateList(){
        $updatePrices = UpdatePrice::with('productModel')->get();
        return view('purchase.update_price.all', compact('updatePrices'));
    }
}

