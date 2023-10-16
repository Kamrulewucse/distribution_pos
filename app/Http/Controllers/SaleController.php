<?php

namespace App\Http\Controllers;

use App\Enumeration\Role;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\MobileBanking;
use App\Models\PurchaseInventory;
use App\Models\PurchaseInventoryLog;
use App\Models\PurchaseOrder;
use App\Models\SalePayment;
use App\Models\SalesOrder;
use App\Models\Service;
use App\Models\SrProductAssignOrder;
use App\Models\SrProductAssignOrderItem;
use App\Models\TransactionLog;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;

class SaleController extends Controller
{
    public function salesOrder() {

        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('sale.sales_order.create', compact('banks',
        'customers'));
    }

    public function salesOrderPost(Request $request) {

        $total = $request->total;

        $rules = [
            'retailer' => 'required',
            'date' => 'required|date',
            'discount' => 'required|numeric|min:0',
            'discount_in_amount' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0|max:'.$total,
            'payment_type' => 'required',
        ];

        if ($request->serial) {
            $rules['imei.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $request->validate($rules);

        $available = true;
        $message = '';
        $counter = 0;

        if ($request->imei) {
            foreach ($request->imei as $imei) {
                $inventory = PurchaseInventory::where('serial_no', $request->imei[$counter])
                    ->where('shop_id', 1)
                    ->first();

                if ($request->quantity[$counter] > $inventory->quantity) {
                    $available = false;
                    $message = 'Insufficient ' . $inventory->productModel->name;
                    break;
                }
                $counter++;
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $order = new SalesOrder();
        $order->order_no = rand(10000000, 99999999);
        $order->customer_id = $request->retailer;
        $order->date = $request->date;
        $order->sub_total = 0;
        $order->discount_type = $request->discount_type;
        $order->discount = $request->discount;
        $order->flat_discount = $request->discount_in_amount;
        $order->discount_type = $request->discount_type;
        $order->payment_type = $request->payment_type;
        $order->sale_type = 1;
        $order->total = 0;
        $order->paid = $request->paid;
        $order->due = 0;
        $order->created_by = Auth::id();
        $order->save();

        $counter = 0;
        $subTotal = 0;
        $buyingPrice = 0;

        if  ($request->imei) {
            foreach ($request->imei as $imei) {
                $inventory = PurchaseInventory::where('serial_no', $request->imei[$counter])
                    ->where('shop_id', 1)
                    ->with('brand')
                    ->with('productModel')
                    ->with('color')
                    ->first();

                $buyingPrice += $inventory->avg_unit_price * $request->quantity[$counter];

                $order->products()->attach($inventory->product->id, [
                    'name' => $inventory->productModel->name,
                    'product_brand_id' => $inventory->product_brand_id,
                    'product_model_id' => $inventory->product_model_id,
                    'product_color_id' => $inventory->product_color_id,
                    'serial' => $request->imei[$counter],
                    'quantity' => $request->quantity[$counter],
                    'unit_price' => $request->unit_price[$counter],
                    'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                ]);

                $inventory->decrement('quantity', $request->quantity[$counter]);

                $inventoryLog = new PurchaseInventoryLog();
                $inventoryLog->product_brand_id = $inventory->product_brand_id;
                $inventoryLog->product_model_id = $inventory->product_model_id;
                $inventoryLog->product_color_id = $inventory->product_color_id;
                $inventoryLog->product_type_id = $inventory->product_type_id;
                $inventoryLog->serial_no = $request->imei[$counter];
                $inventoryLog->type = 2;
                $inventoryLog->date = $request->date;
                $inventoryLog->warehouse_id = $inventory->warehouse_id;
                $inventoryLog->shop_id = $inventory->shop_id;
                $inventoryLog->quantity = $request->quantity[$counter];
                $inventoryLog->unit_price = $request->unit_price[$counter];
                $inventoryLog->sales_order_id = $order->id;
                $inventoryLog->customer_id = $request->retailer;
                $inventoryLog->created_by = Auth::id();
                $inventoryLog->save();

//                if (auth()->user()->role == Role::$SR){
//
//                   $srAssignOrder =  SrProductAssignOrder::
//                        where('sales_representative_id', auth()->user()->sales_representative_id)
//                        ->where('status',0)
//                        ->first();
//
//                   $getCheckSrAssignProductItem = SrProductAssignOrderItem::
//                        where('sr_product_assign_order_id',$srAssignOrder->id)
//                       ->where('product_model_id',$inventory->product_model_id)
//                        ->first();
//
//                    $inventoryLog->sr_product_assign_order_id = $srAssignOrder->id;
//                    $inventoryLog->sr_product_assign_order_item_id = $getCheckSrAssignProductItem->id;
//                    $inventoryLog->save();
//
//                    $getCheckSrAssignProductItem->decrement('assign_quantity',$request->quantity[$counter]);
//                    $getCheckSrAssignProductItem->increment('sale_quantity',$request->quantity[$counter]);
//                }


                $subTotal += $request->quantity[$counter] * $request->unit_price[$counter];
                $counter++;
            }
        }


        if($request->discount_type==1){
            $order->sub_total = $subTotal;
            $discount = ($subTotal * $request->discount) / 100;
            $total = $subTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }else if($request->discount_type==2){
            $order->sub_total = $subTotal;
            $discount = $request->discount_in_amount;
            $total = $subTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }else{
            $order->sub_total = $subTotal;
            $discount = 0;
            $total = $subTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }


//        if (auth()->user()->role == Role::$SR){
//            $srAssignOrder->increment('total_sale_amount',$order->total);
//            $srAssignOrder->increment('total_paid_amount',$request->paid);
//        }

        // Sales Payment
        if ($request->paid > 0) {
            if ($request->payment_type == 1 || $request->payment_type == 3) {
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->transaction_method = $request->payment_type;
                $payment->received_type = 1;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->created_by = Auth::id();
                $payment->save();

                if ($request->payment_type == 1)
                    Cash::first()->increment('amount', $request->paid);
                else
                    MobileBanking::first()->increment('amount', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 1;
                $log->transaction_method = $request->payment_type;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount = $request->paid;
                $log->sale_payment_id = $payment->id;
                $log->created_by = Auth::id();
                $log->save();
            } else {
                $image = 'img/no_image.png';

                if ($request->cheque_image) {
                    // Upload Image
                    $file = $request->file('cheque_image');
                    $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                    $destinationPath = 'public/uploads/sales_payment_cheque';
                    $file->move($destinationPath, $filename);

                    $image = 'uploads/sales_payment_cheque/'.$filename;
                }

                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->transaction_method = 2;
                $payment->received_type = 1;
                $payment->bank_id = $request->bank;
                $payment->branch_id = $request->branch;
                $payment->bank_account_id = $request->account;
                $payment->cheque_no = $request->cheque_no;
                $payment->cheque_image = $image;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->created_by = Auth::id();
                $payment->save();

                BankAccount::find($request->account)->increment('balance', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 1;
                $log->transaction_method = 2;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->bank_id = $request->bank;
                $log->branch_id = $request->branch;
                $log->bank_account_id = $request->account;
                $log->cheque_no = $request->cheque_no;
                $log->cheque_image = $image;
                $log->amount = $request->paid;
                $log->sale_payment_id = $payment->id;
                $log->created_by = Auth::id();
                $log->save();
            }
        }

        // Selling Price log
        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = 'Selling price for '.$order->order_no;
        $log->transaction_type = 4;
        $log->transaction_method = 0;
        $log->account_head_type_id = 5;
        $log->account_head_sub_type_id = 5;
        $log->amount = $total;
        $log->sales_order_id = $order->id;
        $log->created_by = Auth::id();
        $log->save();

        return redirect()->route('sale_receipt.details', ['order' => $order->id]);
    }

    public function srSalesOrder() {

        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('sale.sales_order.sr_sale_create', compact('banks',
            'customers'));
    }

    public function srSalesOrderPost(Request $request) {

        $total = $request->total;

        $rules = [
            'retailer' => 'required',
            'date' => 'required|date',
            'discount' => 'required|numeric|min:0',
            'discount_in_amount' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0|max:'.$total,
            'payment_type' => 'required',
        ];

        if ($request->serial) {
            $rules['imei.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $request->validate($rules);

        $available = true;
        $message = '';
        $counter = 0;

        if ($request->imei) {
            foreach ($request->imei as $imei) {
                $inventory = PurchaseInventory::where('serial_no', $request->imei[$counter])
                    ->where('shop_id', 1)
                    ->first();

                if ($request->quantity[$counter] > $inventory->quantity) {
                    $available = false;
                    $message = 'Insufficient ' . $inventory->productModel->name;
                    break;
                }
                $counter++;
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $order = new SalesOrder();
        $order->order_no = rand(10000000, 99999999);
        $order->customer_id = $request->retailer;
        $order->sr_id = auth()->user()->sales_representative_id;
        $order->date = $request->date;
        $order->sub_total = $total;
        $order->discount_type = $request->discount_type;
        $order->discount = $request->discount;
        $order->flat_discount = $request->discount_in_amount;
        $order->discount_type = $request->discount_type;
        $order->payment_type = $request->payment_type;
        $order->sale_type = 2;
        $order->total = 0;
        $order->paid = $request->paid;
        $order->due = 0;
        $order->created_by = Auth::id();
        $order->save();

        $counter = 0;
        $subTotal = 0;
        $buyingPrice = 0;

        if  ($request->imei) {
            foreach ($request->imei as $imei) {
                $inventory = PurchaseInventory::where('serial_no', $request->imei[$counter])
                    ->where('shop_id', 1)
                    ->with('brand')
                    ->with('productModel')
                    ->with('color')
                    ->first();

                $buyingPrice += $inventory->avg_unit_price * $request->quantity[$counter];

                $order->products()->attach($inventory->product->id, [
                    'name' => $inventory->productModel->name,
                    'product_brand_id' => $inventory->product_brand_id,
                    'product_model_id' => $inventory->product_model_id,
                    'product_color_id' => $inventory->product_color_id,
                    'serial' => $request->imei[$counter],
                    'quantity' => $request->quantity[$counter],
                    'unit_price' => $request->unit_price[$counter],
                    'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                ]);

                $inventory->decrement('quantity', $request->quantity[$counter]);

                $inventoryLog = new PurchaseInventoryLog();
                $inventoryLog->product_brand_id = $inventory->product_brand_id;
                $inventoryLog->product_model_id = $inventory->product_model_id;
                $inventoryLog->product_color_id = $inventory->product_color_id;
                $inventoryLog->product_type_id = $inventory->product_type_id;
                $inventoryLog->serial_no = $request->imei[$counter];
                $inventoryLog->type = 2;
                $inventoryLog->date = $request->date;
                $inventoryLog->warehouse_id = $inventory->warehouse_id;
                $inventoryLog->shop_id = $inventory->shop_id;
                $inventoryLog->quantity = $request->quantity[$counter];
                $inventoryLog->unit_price = $request->unit_price[$counter];
                $inventoryLog->sales_order_id = $order->id;
                $inventoryLog->customer_id = $request->retailer;
                $inventoryLog->created_by = Auth::id();
                $inventoryLog->save();



                    $srAssignOrder =  SrProductAssignOrder::
                    where('sales_representative_id', auth()->user()->sales_representative_id)
                        ->where('status',0)
                        ->first();

                    $getCheckSrAssignProductItem = SrProductAssignOrderItem::
                    where('sr_product_assign_order_id',$srAssignOrder->id)
                        ->where('product_model_id',$inventory->product_model_id)
                        ->first();

                    $inventoryLog->sr_product_assign_order_id = $srAssignOrder->id;
                    $inventoryLog->sr_product_assign_order_item_id = $getCheckSrAssignProductItem->id;
                    $inventoryLog->save();

                    $getCheckSrAssignProductItem->decrement('assign_quantity',$request->quantity[$counter]);
                    $getCheckSrAssignProductItem->increment('sale_quantity',$request->quantity[$counter]);



                $subTotal += $request->quantity[$counter] * $request->unit_price[$counter];
                $counter++;
            }
        }


        if($request->discount_type==1){
            $order->sub_total = $subTotal;
            $discount = ($subTotal * $request->discount) / 100;
            $total = $subTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }else if($request->discount_type==2){
            $order->sub_total = $subTotal;
            $discount = $request->discount_in_amount;
            $total = $subTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }else{
            $order->sub_total = $subTotal;
            $discount = 0;
            $total = $subTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }



            $srAssignOrder->increment('total_sale_amount',$order->total);
            $srAssignOrder->increment('total_paid_amount',$request->paid);


//        // Sales Payment
//        if ($request->paid > 0) {
//            if ($request->payment_type == 1 || $request->payment_type == 3) {
//                $payment = new SalePayment();
//                $payment->sales_order_id = $order->id;
//                $payment->transaction_method = $request->payment_type;
//                $payment->received_type = 1;
//                $payment->amount = $request->paid;
//                $payment->date = $request->date;
//                $payment->created_by = Auth::id();
//                $payment->save();
//
//                if ($request->payment_type == 1)
//                    Cash::first()->increment('amount', $request->paid);
//                else
//                    MobileBanking::first()->increment('amount', $request->paid);
//
//                $log = new TransactionLog();
//                $log->date = $request->date;
//                $log->particular = 'Payment for '.$order->order_no;
//                $log->transaction_type = 1;
//                $log->transaction_method = $request->payment_type;
//                $log->account_head_type_id = 2;
//                $log->account_head_sub_type_id = 2;
//                $log->amount = $request->paid;
//                $log->sale_payment_id = $payment->id;
//                $log->created_by = Auth::id();
//                $log->save();
//            } else {
//                $image = 'img/no_image.png';
//
//                if ($request->cheque_image) {
//                    // Upload Image
//                    $file = $request->file('cheque_image');
//                    $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
//                    $destinationPath = 'public/uploads/sales_payment_cheque';
//                    $file->move($destinationPath, $filename);
//
//                    $image = 'uploads/sales_payment_cheque/'.$filename;
//                }
//
//                $payment = new SalePayment();
//                $payment->sales_order_id = $order->id;
//                $payment->transaction_method = 2;
//                $payment->received_type = 1;
//                $payment->bank_id = $request->bank;
//                $payment->branch_id = $request->branch;
//                $payment->bank_account_id = $request->account;
//                $payment->cheque_no = $request->cheque_no;
//                $payment->cheque_image = $image;
//                $payment->amount = $request->paid;
//                $payment->date = $request->date;
//                $payment->created_by = Auth::id();
//                $payment->save();
//
//                BankAccount::find($request->account)->increment('balance', $request->paid);
//
//                $log = new TransactionLog();
//                $log->date = $request->date;
//                $log->particular = 'Payment for '.$order->order_no;
//                $log->transaction_type = 1;
//                $log->transaction_method = 2;
//                $log->account_head_type_id = 2;
//                $log->account_head_sub_type_id = 2;
//                $log->bank_id = $request->bank;
//                $log->branch_id = $request->branch;
//                $log->bank_account_id = $request->account;
//                $log->cheque_no = $request->cheque_no;
//                $log->cheque_image = $image;
//                $log->amount = $request->paid;
//                $log->sale_payment_id = $payment->id;
//                $log->created_by = Auth::id();
//                $log->save();
//            }
//        }
//
//        // Selling Price log
//        $log = new TransactionLog();
//        $log->date = $request->date;
//        $log->particular = 'Selling price for '.$order->order_no;
//        $log->transaction_type = 4;
//        $log->transaction_method = 0;
//        $log->account_head_type_id = 5;
//        $log->account_head_sub_type_id = 5;
//        $log->amount = $total;
//        $log->sales_order_id = $order->id;
//        $log->created_by = Auth::id();
//        $log->save();

        return redirect()->route('sale_receipt.details', ['order' => $order->id]);
    }

    public function srSaleApprovePost(Request $request){

        // Sales Payment

       DB::table('sales_orders')
            ->where('id',$request->id)
            ->update([
                'sale_type' => 3,
            ]);

       $order =SalesOrder::where('id',$request->id)->first();

        if ($order->paid > 0) {
            if ($order->payment_type == 1 || $order->payment_type == 3) {
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->transaction_method = $order->payment_type;
                $payment->received_type = 1;
                $payment->amount = $order->paid;
                $payment->date = $order->date;
                $payment->created_by = Auth::id();
                $payment->save();

                if ($order->payment_type == 1)
                    Cash::first()->increment('amount', $order->paid);
                else
                    MobileBanking::first()->increment('amount', $order->paid);

                $log = new TransactionLog();
                $log->date = $order->date;
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 1;
                $log->transaction_method = $order->payment_type;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount = $order->paid;
                $log->sale_payment_id = $payment->id;
                $log->created_by = $order->sr_id;
                $log->save();
            }
//            else {
//                $image = 'img/no_image.png';
//
//                if ($request->cheque_image) {
//                    // Upload Image
//                    $file = $request->file('cheque_image');
//                    $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
//                    $destinationPath = 'public/uploads/sales_payment_cheque';
//                    $file->move($destinationPath, $filename);
//
//                    $image = 'uploads/sales_payment_cheque/'.$filename;
//                }
//
//                $payment = new SalePayment();
//                $payment->sales_order_id = $order->id;
//                $payment->transaction_method = 2;
//                $payment->received_type = 1;
//                $payment->bank_id = $request->bank;
//                $payment->branch_id = $request->branch;
//                $payment->bank_account_id = $request->account;
//                $payment->cheque_no = $request->cheque_no;
//                $payment->cheque_image = $image;
//                $payment->amount = $request->paid;
//                $payment->date = $request->date;
//                $payment->created_by = Auth::id();
//                $payment->save();
//
//                BankAccount::find($request->account)->increment('balance', $request->paid);
//
//                $log = new TransactionLog();
//                $log->date = $request->date;
//                $log->particular = 'Payment for '.$order->order_no;
//                $log->transaction_type = 1;
//                $log->transaction_method = 2;
//                $log->account_head_type_id = 2;
//                $log->account_head_sub_type_id = 2;
//                $log->bank_id = $request->bank;
//                $log->branch_id = $request->branch;
//                $log->bank_account_id = $request->account;
//                $log->cheque_no = $request->cheque_no;
//                $log->cheque_image = $image;
//                $log->amount = $request->paid;
//                $log->sale_payment_id = $payment->id;
//                $log->created_by = Auth::id();
//                $log->save();
//            }
        }

        // Selling Price log
        $log = new TransactionLog();
        $log->date = $order->date;
        $log->particular = 'Selling price for '.$order->order_no;
        $log->transaction_type = 4;
        $log->transaction_method = 0;
        $log->account_head_type_id = 5;
        $log->account_head_sub_type_id = 5;
        $log->amount = $order->sub_total;
        $log->sales_order_id = $order->id;
        $log->created_by = $order->sr_id;
        $log->save();

        return response()->json(['success' => true, 'message' => 'Approve Successfully.']);


    }


    public function saleReceipt() {
        $banks = Bank::where('status', 1)->orderBy('name')->get();

        return view('sale.receipt.all', compact('banks'));
    }

    public function makePayment(Request $request) {
        $rules = [
            'order' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        if ($request->order != '') {
            $order = SalesOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;
        }

        if ($request->amount < $order->due)
            $rules['next_payment_date'] = 'required|date';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = SalesOrder::find($request->order);

        if ($request->payment_type == 1 || $request->payment_type == 3) {
            $payment = new SalePayment();
            $payment->sales_order_id = $order->id;
            $payment->transaction_method = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->created_by = Auth::id();
            $payment->save();

            if ($request->payment_type == 1)
                Cash::first()->increment('amount', $request->amount);
            else
                MobileBanking::first()->increment('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Payment from '.$order->customer->name.' for '.$order->order_no;
            $log->transaction_type = 1;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->created_by = Auth::id();
            $log->save();
        } else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/sales_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/sales_payment_cheque/'.$filename;
            }

            $payment = new SalePayment();
            $payment->sales_order_id = $order->id;
            $payment->transaction_method = 2;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->cheque_image = $image;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->created_by = Auth::id();
            $payment->save();

            BankAccount::find($request->account)->increment('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Payment from '.$order->customer->name.' for '.$order->order_no;
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->created_by = Auth::id();
            $log->save();
        }

        $order->increment('paid', $request->amount);
        $order->decrement('due', $request->amount);

        if ($order->due > 0) {
            $order->next_payment = $request->next_payment_date;
        } else {
            $order->next_payment = null;
        }

        $order->save();

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function saleReceiptDetails(SalesOrder $order) {
        return view('sale.receipt.details', compact('order'));
    }

    public function saleReceiptPrint(SalesOrder $order) {
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');

        return view('sale.receipt.print', compact('order'));
    }
    public function saleReceiptPdf(SalesOrder $order) {

        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        $pdf = PDF::loadView('sale.receipt.pdf',compact('order'));
        return $pdf->download($order->order_no.'.pdf');

    }

    public function salePaymentDetails(SalePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('sale.receipt.payment_details', compact('payment'));
    }

    public function salePaymentPrint(SalePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('sale.receipt.payment_print', compact('payment'));
    }


    public function customerPayment() {
        $banks = Bank::where('status', 1)->orderBy('name')->get();

        return view('sale.customer_payment.all', compact('banks'));
    }

    public function customerPaymentGetOrders(Request $request) {
        $orders = SalesOrder::where('customer_id', $request->customerId)
            ->where('due', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function customerPaymentGetRefundOrders(Request $request) {
        $orders = SalesOrder::where('customer_id', $request->customerId)
            ->where('refund', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function customerMakePayment(Request $request) {
        $rules = [
            'order' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        if ($request->order != '') {
            $order = SalesOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;
        }

        if ($request->amount < $order->due)
            $rules['next_payment_date'] = 'required|date';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = SalesOrder::find($request->order);

        if ($request->payment_type == 1 || $request->payment_type == 3) {
            $payment = new SalePayment();
            $payment->sales_order_id = $order->id;
            $payment->transaction_method = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();

            if ($request->payment_type == 1)
                Cash::first()->increment('amount', $request->amount);
            else
                MobileBanking::first()->increment('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Payment from '.$order->customer->name.' for '.$order->order_no;
            $log->transaction_type = 1;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->save();
        } else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/sales_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/sales_payment_cheque/'.$filename;
            }

            $payment = new SalePayment();
            $payment->sales_order_id = $order->id;
            $payment->transaction_method = 2;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->cheque_image = $image;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();

            BankAccount::find($request->account)->increment('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Payment from '.$order->customer->name.' for '.$order->order_no;
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->save();
        }

        $order->increment('paid', $request->amount);
        $order->decrement('due', $request->amount);

        if ($order->due > 0) {
            $order->next_payment = $request->next_payment_date;
        } else {
            $order->next_payment = null;
        }

        $order->save();

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function customerMakeRefund(Request $request) {
        $rules = [
            'order' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        if ($request->order != '') {
            $order = SalesOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:' . $order->refund;
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            if ($request->payment_type == 1) {
                $cash = Cash::first();

                if ($request->amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            } else {
                if ($request->account != '') {
                    $account = BankAccount::find($request->account);

                    if ($request->amount > $account->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = SalesOrder::find($request->order);

        if ($request->payment_type == 1 || $request->payment_type == 3) {
            $payment = new SalePayment();
            $payment->sales_order_id = $order->id;
            $payment->type = 2;
            $payment->transaction_method = $request->payment_type;
            $payment->received_type = 1;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();

            if ($request->payment_type == 1)
                Cash::first()->decrement('amount', $request->amount);
            else
                MobileBanking::first()->decrement('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Refund to '.$order->customer->name.' for '.$order->order_no;
            $log->transaction_type = 6;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 6;
            $log->account_head_sub_type_id = 6;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->save();
        } else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/sales_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/sales_payment_cheque/'.$filename;
            }

            $payment = new SalePayment();
            $payment->sales_order_id = $order->id;
            $payment->type = 2;
            $payment->transaction_method = 2;
            $payment->received_type = 1;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->cheque_image = $image;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();

            BankAccount::find($request->account)->decrement('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Refund to '.$order->customer->name.' for '.$order->order_no;
            $log->transaction_type = 6;
            $log->transaction_method = 2;
            $log->account_head_type_id = 6;
            $log->account_head_sub_type_id = 6;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->save();
        }

        $order->decrement('refund', $request->amount);

        return response()->json(['success' => true, 'message' => 'Refund has been completed.', 'redirect_url' => route('sale_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function saleInformation() {
        return view('sale.product_sale_information.index');
    }

    public function saleInformationPost(Request $request) {
        $product = DB::table('purchase_order_purchase_product')
            ->where('serial_no', $request->serial)
            ->first();

        $sale = DB::table('purchase_product_sales_order')
            ->where('serial', $request->serial)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Invalid serial.']);
        } elseif ($product->quantity > 1) {
            return response()->json(['success' => false, 'message' => 'This serial has many products.']);
        } elseif (!$sale) {
            return response()->json(['success' => false, 'message' => 'This serial not sell yet.']);
        } else {
            $order = SalesOrder::find($sale->sales_order_id);
            $purchaseOrder = PurchaseOrder::find($product->purchase_order_id);

            return response()->json(['success' => true, 'message' => 'This serial is sold.', 'redirect_url' => route('sale_information.print', ['purchaseOrder' => $purchaseOrder->id, 'saleOrder' => $order->id, 'serial' => $request->serial])]);
        }
    }

    public function saleInformationPrint(PurchaseOrder $purchaseOrder, SalesOrder $saleOrder) {
        $saleOrder->amount_in_word = DecimalToWords::convert($saleOrder->total,'Taka',
            'Poisa');

        return view('sale.product_sale_information.print', compact('purchaseOrder',
            'saleOrder'));
    }

    public function saleReceiptEdit(SalesOrder $order) {
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('sale.receipt.edit', compact('order', 'warehouses',
            'banks', 'customers'));
    }

    public function saleReceiptEditPost(SalesOrder $order, Request $request) {
        $total = $request->total;
        $due = $request->due_total;
        $refund = $request->refund;

        $rules = [
            'date' => 'required|date',
            'vat' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
        ];

        if ($request->serial) {
            $rules['serial.*'] = 'required';
            $rules['product_name.*'] = 'required';
            $rules['category_name.*'] = 'required';
            $rules['subcategory_name.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }


        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $request->validate($rules);

        $available = true;
        $message = '';
        $counter = 0;

        $previousSerials = [];

        foreach ($order->products as $product)
            $previousSerials[] = $product->pivot->serial;

        if ($request->serial) {
            foreach ($request->serial as $serial) {
                if (in_array($serial, $previousSerials)) {
                    $inventory = PurchaseInventory::where('serial_no', $request->serial[$counter])
                        //->where('warehouse_id', $request->warehouse)
                        ->first();

                    $orderProduct = DB::table('purchase_product_sales_order')
                        ->where('sales_order_id', $order->id)
                        ->where('serial', $serial)
                        ->first();
                    if ($request->quantity[$counter] - $orderProduct->quantity > $inventory->quantity) {
                        $available = false;
                        $message = 'Insufficient ' . $inventory->product->name;
                        break;
                    }
                } else {
                    $inventory = PurchaseInventory::where('serial_no', $request->serial[$counter])
                        //->where('warehouse_id', $request->warehouse)
                        ->first();

                    if ($request->quantity[$counter] > $inventory->quantity) {
                        $available = false;
                        $message = 'Insufficient ' . $inventory->product->name;
                        break;
                    }
                }
                $counter++;
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $counter = 0;
        $subTotal = 0;
        $buyingPrice = 0;

        if  ($request->serial) {
            foreach ($request->serial as $serial) {
                if (in_array($serial, $previousSerials)) {
                    // Old Item

                    $purchaseProduct = DB::table('purchase_product_sales_order')
                        ->where('serial', $serial)
                        ->where('sales_order_id', $order->id)
                        ->first();

                    DB::table('purchase_product_sales_order')
                        ->where('serial', $serial)
                        ->where('sales_order_id', $order->id)
                        ->update([
                            'quantity' => $request->quantity[$counter],
                            'unit_price' => $request->unit_price[$counter],
                            'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                        ]);

                    $subTotal += $request->quantity[$counter] * $request->unit_price[$counter];

                    // Inventory
                    $inventory = PurchaseInventory::where('serial_no', $serial)->first();

                    if ($request->quantity[$counter] > $purchaseProduct->quantity)
                        $inventory->quantity = ($inventory->quantity + $purchaseProduct->quantity) - $request->quantity[$counter];
                    elseif ($request->quantity[$counter] < $purchaseProduct->quantity) {
                        $diff = $purchaseProduct->quantity - $request->quantity[$counter];
                        $inventory->quantity = $inventory->quantity + $diff;
                    }
                    $inventory->save();

                    if ($request->quantity[$counter] != $purchaseProduct->quantity) {
                        $inventoryLog = new PurchaseInventoryLog();
                        $inventoryLog->purchase_product_id = $purchaseProduct->purchase_product_id;

                        if ($request->quantity[$counter] > $purchaseProduct->quantity) {
                            $inventoryLog->type = 3;
                            $inventoryLog->quantity = $request->quantity[$counter] - $purchaseProduct->quantity;
                        } else {
                            $inventoryLog->type = 4;
                            $inventoryLog->quantity = $purchaseProduct->quantity - $request->quantity[$counter];
                        }

                        $inventoryLog->date = date('Y-m-d');
                        $inventoryLog->warehouse_id = $inventory->warehouse_id;
                        $inventoryLog->unit_price = $inventory->unit_price;
                        $inventoryLog->sales_order_id = $order->id;
                        $inventoryLog->save();
                    }

                    if (($key = array_search($serial, $previousSerials)) !== false) {
                        unset($previousSerials[$key]);
                    }
                } else {
                    // New Item
                    $inventory = PurchaseInventory::where('serial_no', $serial)
                        //->where('warehouse_id', $request->warehouse)
                        ->with('product')
                        ->first();
                    $buyingPrice += $inventory->avg_unit_price * $request->quantity[$counter];

                    $order->products()->attach($inventory->product->id, [
                        'name' => $inventory->product->name,
                        'purchase_product_category_name' => $inventory->category->name,
                        'purchase_product_sub_category_name' => $inventory->subcategory->name,
                        'serial' => $request->serial[$counter],
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);

                    $inventory->decrement('quantity', $request->quantity[$counter]);
                    $inventory->decrement('total', $buyingPrice);

                    $inventoryLog = new PurchaseInventoryLog();
                    $inventoryLog->purchase_product_id = $inventory->product->id;
                    $inventoryLog->type = 3;
                    $inventoryLog->date = date('Y-m-d');
                    $inventoryLog->warehouse_id = $request->warehouse;
                    $inventoryLog->quantity = $request->quantity[$counter];
                    $inventoryLog->unit_price = $request->unit_price[$counter];
                    $inventoryLog->sales_order_id = $order->id;
                    $inventoryLog->save();

                    $subTotal += $request->quantity[$counter] * $request->unit_price[$counter];
                }

                $counter++;
            }
        }

        // Delete items
        foreach ($previousSerials as $serial) {
            $purchaseProduct = DB::table('purchase_product_sales_order')->where('sales_order_id', $order->id)
                ->where('serial', $serial)->first();

            PurchaseInventory::where('serial_no', $serial)->increment('quantity', $purchaseProduct->quantity);

            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->purchase_product_id = $purchaseProduct->purchase_product_id;
            $inventoryLog->type = 4;
            $inventoryLog->quantity = $purchaseProduct->quantity;
            $inventoryLog->date = date('Y-m-d');
            $inventoryLog->warehouse_id = $request->warehouse;
            $inventoryLog->unit_price = $purchaseProduct->unit_price;
            $inventoryLog->sales_order_id = $order->id;
            $inventoryLog->save();

            DB::table('purchase_product_sales_order')->where('sales_order_id', $order->id)
                ->where('serial', $serial)->delete();
        }


        // Update Order

        $order->date = $request->date;
        $order->sub_total = $subTotal;
        $order->vat_percentage = $request->vat;
        $vat = ($subTotal * $request->vat) / 100;
        $order->discount = $request->discount;
        $discount = ($subTotal * $request->discount) / 100;
        $order->vat = $vat;
        $total = $subTotal  + $vat - $discount;
        $order->total = $total;
        $order->created_by = Auth::user()->id;
        $order->due = 0;
        $order->refund = $refund;
        $order->save();

        if ($due > 0) {
            if ($request->payment_type == 1 || $request->payment_type == 3) {
                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->transaction_method = $request->payment_type;
                $payment->received_type = 1;
                $payment->amount = $due;
                $payment->date = date('Y-m-d');
                $payment->save();

                if ($request->payment_type == 1)
                    Cash::first()->increment('amount', $due);
                else
                    MobileBanking::first()->increment('amount', $due);

                $order->increment('paid',$due);

                $log = new TransactionLog();
                $log->date = date('Y-m-d');
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 1;
                $log->transaction_method = $request->payment_type;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->amount = $due;
                $log->sale_payment_id = $payment->id;
                $log->save();
            } else {
                $image = 'img/no_image.png';

                if ($request->cheque_image) {
                    // Upload Image
                    $file = $request->file('cheque_image');
                    $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                    $destinationPath = 'public/uploads/sales_payment_cheque';
                    $file->move($destinationPath, $filename);

                    $image = 'uploads/sales_payment_cheque/'.$filename;
                }

                $payment = new SalePayment();
                $payment->sales_order_id = $order->id;
                $payment->transaction_method = 2;
                $payment->received_type = 1;
                $payment->bank_id = $request->bank;
                $payment->branch_id = $request->branch;
                $payment->bank_account_id = $request->account;
                $payment->cheque_no = $request->cheque_no;
                $payment->cheque_image = $image;
                $payment->amount = $due;
                $payment->date = date('Y-m-d');
                $payment->save();

                BankAccount::find($request->account)->increment('balance', $due);
                $order->increment('paid',$due);

                $log = new TransactionLog();
                $log->date = date('Y-m-d');
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 1;
                $log->transaction_method = 2;
                $log->account_head_type_id = 2;
                $log->account_head_sub_type_id = 2;
                $log->bank_id = $request->bank;
                $log->branch_id = $request->branch;
                $log->bank_account_id = $request->account;
                $log->cheque_no = $request->cheque_no;
                $log->cheque_image = $image;
                $log->amount = $due;
                $log->sale_payment_id = $payment->id;
                $log->save();
            }
        }

        if ($buyingPrice > 0) {
            // Buying Price log
            $log = new TransactionLog();
            $log->date = date('Y-m-d');
            $log->particular = 'Buying price for '.$order->order_no;
            $log->transaction_type = 4;
            $log->transaction_method = 0;
            $log->account_head_type_id = 5;
            $log->account_head_sub_type_id = 5;
            $log->amount = $buyingPrice;
            $log->sales_order_id = $order->id;
            $log->save();
        }


        return redirect()->route('sale_receipt.details', ['order' => $order->id]);
    }

    public function saleProductDetails(Request $request) {

        if (auth()->user()->role == 1){
            $product = PurchaseInventory::with('productModel','color','brand','productType')
                ->where('serial_no', $request->imeiNo)
                ->where('quantity', '>', 0)
                ->where('shop_id', 1)
                ->orWhereHas('productModel', function($q) use ($request){
                    $q->where('name', $request->imeiNo);
                })
                ->first();
            if ($product) {
                $product = $product->toArray();
                //dd($product);
                return response()->json(['success' => true, 'data' => $product, 'count' => $product['quantity']]);
            } else {
                return response()->json(['success' => false, 'message' => 'Not Available.']);
            }
        }else{

            $srAssignOrder = SrProductAssignOrder::with('srAssignProductItem')
                ->where('sales_representative_id',auth()->user()->sales_representative_id)
                ->where('status',0)
                ->first();

            if ($srAssignOrder){
                $product = [];
                $srAssignProductModelIds = $srAssignOrder->srAssignProductItem->pluck('product_model_id')->toArray();

                $product = PurchaseInventory::with('productModel','color','brand','productType')
                    ->whereIn('product_model_id',$srAssignProductModelIds)
                    ->where('serial_no', $request->imeiNo)
                    ->where('quantity', '>', 0)
                    ->where('shop_id', 1)
                    ->orWhereHas('productModel', function($q) use ($request){
                        $q->where('name', $request->imeiNo);
                    })
                    ->first();


                if ($product){
                    $checkSrAssignProductModel = SrProductAssignOrderItem::where('sr_product_assign_order_id',$srAssignOrder->id)
                        ->where('product_model_id',$product->product_model_id)
                        ->first();

                    if ($checkSrAssignProductModel->assign_quantity <= 0){
                        return response()->json(['success' => false, 'message' => 'Insufficient Quantity.']);
                    }

                    return response()->json(['success' => true, 'data' => $product, 'count' => $product['quantity'] = $checkSrAssignProductModel->assign_quantity]);

                }else{
                    return response()->json(['success' => false, 'message' => 'Not found.']);

                }
            }else{
                return response()->json(['success' => false, 'message' => 'Not Available.']);
            }


        }

    }

    public function saleReceiptDatatable() {
        $query = SalesOrder::with('customer');

        return DataTables::eloquent($query)
            ->addColumn('customer_name', function(SalesOrder $order) {
                return $order->customer->name;
            })
            ->addColumn('customer_address', function(SalesOrder $order) {
                return $order->customer->address;
            })
            ->addColumn('action', function(SalesOrder $order) {
                if ($order->due > 0) {
                    return '<a href="' . route('sale_receipt.details', ['order' => $order->id]) . '" class="btn btn-primary btn-sm">View</a> <a role="button" class="btn btn-success btn-sm btn-payment" data-id="' . $order->id . '">Payment</a>';
//                    return '<a href="' . route('sale_receipt.details', ['order' => $order->id]) . '" class="btn btn-primary btn-sm">View</a> <a role="button" class="btn btn-success btn-sm btn-payment" data-id="' . $order->id . '">Payment</a> <a class="btn btn-info btn-sm" href="'.route('sale_receipt.edit', ['order' => $order->id]).'">Edit</a>';
                    //return '<a href="'.route('sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a>';
                } else
                    return '<a href="'.route('sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a>';
//                    return '<a href="'.route('sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a class="btn btn-info btn-sm" href="'.route('sale_receipt.edit', ['order' => $order->id]).'">Edit</a>';
            })
            ->editColumn('date', function(SalesOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->editColumn('next_payment', function(SalesOrder $order) {
                if ($order->next_payment)
                    return $order->next_payment->format('j F, Y');
                else
                    return '';
            })
            ->editColumn('total', function(SalesOrder $order) {
                return '৳'.number_format($order->total, 2);
            })
            ->editColumn('paid', function(SalesOrder $order) {
                return '৳'.number_format($order->paid, 2);
            })
            ->editColumn('due', function(SalesOrder $order) {
                return '৳'.number_format($order->due, 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function customerPaymentDatatable() {
        $query = Customer::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(Customer $customer) {
                $btns = '<a class="btn btn-info btn-sm btn-pay" role="button" data-id="'.$customer->id.'" data-name="'.$customer->name.'">Payment</a>';

                if ($customer->refund > 0)
                    $btns .= ' <a class="btn btn-danger btn-sm btn-refund" role="button" data-id="'.$customer->id.'" data-name="'.$customer->name.'">Refund</a>';

                return $btns;
            })
            ->addColumn('paid', function(Customer $customer) {
                return number_format($customer->paid, 2);
            })
            ->addColumn('due', function(Customer $customer) {
                return number_format($customer->due, 2);
            })
            ->addColumn('total', function(Customer $customer) {
                return number_format($customer->total, 2);
            })
            ->addColumn('refund', function(Customer $customer) {
                return number_format($customer->refund, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
