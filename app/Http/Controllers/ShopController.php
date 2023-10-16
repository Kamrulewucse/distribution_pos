<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    public function index() {
        $shops = Shop::all();
        return view('purchase.shop.all', compact('shops'));
    }


    public function add() {
        return view('purchase.shop.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required',
            'address' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->mobile_no = $request->mobile_no;
        $shop->address = $request->address;
        $shop->status = $request->status;
        $shop->save();

        return redirect()->route('shop')->with('message', 'Shop add successfully.');
    }

    public function edit(Shop $shop) {
        return view('purchase.shop.edit', compact('shop'));
    }

    public function editPost(Shop $shop, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required',
            'address' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $shop->name = $request->name;
        $shop->mobile_no = $request->mobile_no;
        $shop->address = $request->address;
        $shop->status = $request->status;
        $shop->save();

        return redirect()->route('shop')->with('message', 'Shop edit successfully.');
    }

}
