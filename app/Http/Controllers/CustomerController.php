<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();
        return view('sale.customer.all',compact('customers'));
    }

    public function add() {
        return view('sale.customer.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'shop_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->mobile_no = $request->mobile_no;
        $customer->address = $request->address;
        $customer->shop_name = $request->shop_name;
        $customer->email = $request->email;
        $customer->save();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->retailer_id = $customer->id;
        $user->password = bcrypt($request->password);
        $user->role = 3;
        $user->save();

        $customer->user_id = $user->id;
        $customer->save();


        return redirect()->route('customer')->with('message', 'Retailer add successfully.');
    }

    public function edit(Customer $customer) {
        return view('sale.customer.edit', compact('customer'));
    }

    public function editPost(Customer $customer, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'shop_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $customer->name = $request->name;
        $customer->mobile_no = $request->mobile_no;
        $customer->address = $request->address;
        $customer->shop_name = $request->shop_name;
        $customer->email = $request->email;
        $customer->save();

        $user = User::where('id',$customer->user_id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->retailer_id = $customer->id;
        $user->password = bcrypt($request->password);
        $user->save();

        $customer->user_id = $user->id;
        $customer->save();

        return redirect()->route('customer')->with('message', 'Retailer edit successfully.');
    }

    public function datatable() {
        $query = Customer::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(Customer $customer) {
                return '<a class="btn btn-info btn-sm" href="'.route('customer.edit', ['customer' => $customer->id]).'">Edit</a>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
