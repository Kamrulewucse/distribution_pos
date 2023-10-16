<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoustomerService extends Controller
{
    public function index(){
        $customerServices = CustomerService::all();
        return view('customer_service.all', compact('customerServices'));
    }

    public function add() {
        return view('customer_service.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'shop_name' => 'nullable|string|max:255',
        ]);

        $customer_service = new CustomerService();
        $customer_service->name = $request->name;
        $customer_service->mobile_name = $request->mobile_name;
        $customer_service->mobile_no = $request->mobile_no;
        $customer_service->address = $request->address;
        $customer_service->date = $request->date;
        $customer_service->delivery_date = $request->delivery_date;
        $customer_service->note = $request->note;
        $customer_service->status = 0;
        $customer_service->save();

        return redirect()->route('customer_service')->with('message', 'Customer Service add successfully.');
    }

    public function edit(CustomerService $customerService) {

        return view('customer_service.edit', compact('customerService'));
    }

    public function editPost(CustomerService $customerService, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'shop_name' => 'nullable|string|max:255',
        ]);


        $customerService->name = $request->name;
        $customerService->mobile_name = $request->mobile_name;
        $customerService->mobile_no = $request->mobile_no;
        $customerService->address = $request->address;
        $customerService->date = $request->date;
        $customerService->delivery_date = $request->delivery_date;
        $customerService->note = $request->note;
        $customerService->status = 0;
        $customerService->save();

        return redirect()->route('customer_service')->with('message', 'Customer Service edit successfully.');
    }

    public function pending(Request $request){
      DB::table('customer_services')
            ->where('id',$request->id)
            ->update([
                'status' => 1,
            ]);


        return response()->json(['success' => true, 'message' => 'Pending Successful.']);
    }
    public function receiveFromCompany(Request $request){
      DB::table('customer_services')
            ->where('id',$request->id)
            ->update([
                'status' => 2,
            ]);


        return response()->json(['success' => true, 'message' => 'Receive Successful.']);
    }

    public function deliveryComplete(Request $request){

        $request->validate([
            'delivery_note' => 'required|string|max:255',
        ]);

      DB::table('customer_services')
            ->where('id',$request->customer_id)
            ->update([
                'status' => 3,
                'delivery_date' => $request->delivery_date,
                'note' => $request->delivery_note,
            ]);


        return response()->json(['success' => true, 'message' => 'Delivery Complete successfully.']);
    }
    public function delete(Request $request){

      DB::table('customer_services')
            ->where('id',$request->id)
            ->delete();


        return response()->json(['success' => true, 'message' => 'Delete successfully.']);
    }

}
