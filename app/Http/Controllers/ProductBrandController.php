<?php

namespace App\Http\Controllers;

use App\Models\ProductBrand;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductBrandController extends Controller
{
    public function index() {
        $productBrands = ProductBrand::all();
        return view('purchase.product_brand.all', compact('productBrands'));
    }

    public function add() {
        $productTypes = ProductType::all();
        return view('purchase.product_brand.add', compact('productTypes'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required',
            'status' => 'required'
        ]);
        $productBrand = new ProductBrand();
        $productBrand->name = $request->name;
        $productBrand->product_type_id = $request->product_type;
        $productBrand->status = $request->status;
        $productBrand->save();

        return redirect()->route('purchase_product_category')->with('message', 'Brand add successfully.');
    }

    public function edit(ProductBrand $productBrand) {
        $productTypes=ProductType::all();
        return view('purchase.product_brand.edit', compact('productBrand','productTypes'));
    }

    public function editPost(ProductBrand $productBrand, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required',
            'status' => 'required'
        ]);

        $productBrand->name = $request->name;
        $productBrand->product_type_id = $request->product_type;
        $productBrand->status = $request->status;
        $productBrand->save();

        return redirect()->route('purchase_product_category')->with('message', 'Brand edit successfully.');
    }
}
