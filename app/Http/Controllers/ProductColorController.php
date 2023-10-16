<?php

namespace App\Http\Controllers;

use App\Models\ProductColor;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
    public function index() {
        $productColors = ProductColor::all();

        return view('purchase.product_color.all', compact('productColors'));
    }
    public function add() {
        return view('purchase.product_color.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $productColor = new ProductColor();
        $productColor->name = $request->name;
        $productColor->status = $request->status;
        $productColor->save();

        return redirect()->route('product_color')->with('message', 'Color add successfully.');
    }

    public function edit(ProductColor $productColor) {

        return view('purchase.product_color.edit', compact('productColor'));
    }

    public function editPost(ProductColor $productColor, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $productColor->name = $request->name;
        $productColor->status = $request->status;
        $productColor->save();

        return redirect()->route('product_color')->with('message', 'Color edit successfully.');
    }
}


