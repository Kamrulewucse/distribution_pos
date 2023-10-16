<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\MobileBanking;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use App\Models\ProductModel;
use App\Models\ProductType;
use App\Models\PurchaseInventory;
use App\Models\PurchaseInventoryLog;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPurchaseProduct;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Models\PurchaseProductCategory;
use App\Models\Shop;
use App\Models\SrProductAssignOrder;
use App\Models\SrProductAssignOrderItem;
use App\Models\StockTransferOrder;
use App\Models\Supplier;
use App\Models\TransactionLog;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;


class PurchaseController extends Controller
{
    public function purchaseOrder() {
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $productTypes = ProductType::where('status',1)->get();
        $colors = ProductColor::where('status',1)->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        return view('purchase.purchase_order.create', compact('suppliers',
            'warehouses','productTypes','colors','banks'));
    }
    public function purchaseOrder1() {
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $productTypes = ProductType::where('status',1)->get();
        $colors = ProductColor::where('status',1)->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        return view('purchase.purchase_order.create1', compact('suppliers',
            'warehouses','productTypes','colors','banks'));
    }

    public function purchaseOrderEdit(Request $request, $id)
    {
        $purchase_order = PurchaseOrder::where('id', $id)->first();
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $products = PurchaseProduct::where('status', 1)->orderBy('name')->get();
        $categories = PurchaseProductCategory::where('status',1)->get();
        return view('purchase.purchase_order.edit', compact('purchase_order','suppliers',
            'warehouses', 'products','categories'));
    }

    public function purchaseOrderPost(Request $request) {
        //dd($request->all());

        $rules = [
            'supplier' => 'required',
            'warehouse' => 'required',
            'date' => 'required|date',
            'product_type.*' => 'required',
            'product_color.*' => 'required',
            'product_brand.*' => 'required',
            'product_model.*' => 'required',
            'quantity.*' => 'required|numeric|min:1',
            'unit_price.*' => 'required|numeric|min:0',
            'selling_price.*' => 'required|numeric|min:0',
        ];

        $request->validate($rules);

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $available = true;
        $message = '';

        if ($request->product_type) {
            foreach ($request->product_type as $index => $product_type) {
                $productType = ProductType::where('id',$product_type)->first();
                if ($productType->imei != 0){
                    if ($request->imei_code[$index] == null || $request->product_color[$index] == null){
                        $productName = ProductType::where('id',$product_type)->first();
                        $productColor = ProductColor::where('id',$request->product_color[$index])->first();
                        $available = false;
                        if ($productName){
                            $message = 'IMEI Code is Required of this Product Type '. '->' . $productName->name;
                        }else{
                            $message = 'IMEI Code is Required of this Product Type '. '->' . $productColor->name;
                        }
                        break;
                    }
                }
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $order = new PurchaseOrder();
        //$order->order_no = rand(10000000, 99999999);
        $order->supplier_id = $request->supplier;
        $order->warehouse_id = $request->warehouse;
        $order->date = $request->date;
        $order->sub_total = 0;
        $order->discount_type = $request->discount_type;
        $order->discount = $request->discount;
        $order->flat_discount = $request->discount_in_amount;
        $order->discount_type = $request->discount_type;
        $order->total = 0;
        $order->paid = $request->paid;
        $order->due = 0;
        $order->save();
        $order->order_no = str_pad($order->id, 8, 0, STR_PAD_LEFT);
        $order->save();

        $counter = 0;
        $total = 0;
        $imeTotal = 0;

        foreach ($request->product_model as $reqProduct) {
            $product = ProductModel::find($reqProduct);
            $productType = ProductType::where('id',$request->product_type[$counter])->first();
            if ($productType->imei == 0){
                $inventory = PurchaseInventory::where('product_model_id', $product->id)
                    ->where('product_brand_id',$request->product_brand[$counter])
                    ->where('product_type_id',$request->product_type[$counter])
                    ->where('warehouse_id',$request->warehouse)
                    ->first();
                if ($inventory){
                    $inventory->increment('quantity',$request->quantity[$counter]);
                    $inventory->update([
                        'last_unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                    ]);

                    PurchaseOrderPurchaseProduct::create([
                        'purchase_order_id' => $order->id,
                        'product_type_id' => $request->product_type[$counter],
                        'product_brand_id' => $request->product_brand[$counter],
                        'product_color_id' => $request->product_color[$counter] ?? null,
                        'product_model_id' => $product->id,
                        'warehouse_id' => $request->warehouse,
                        'name' => $product->name,
                        'serial_no' => $inventory->serial_no,
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);

                }else {

                    $accessoriesSerialNo = mt_rand(100000, 999999);

                    $inventory = new PurchaseInventory();
                    $inventory->product_type_id = $request->product_type[$counter];
                    $inventory->product_brand_id = $request->product_brand[$counter];
                    $inventory->product_color_id = $request->product_color[$counter] ?? null;
                    $inventory->product_model_id = $product->id;
                    $inventory->warehouse_id = $request->warehouse;
                    $inventory->quantity = $request->quantity[$counter];
                    $inventory->serial_no = $accessoriesSerialNo;
                    $inventory->last_unit_price = $request->unit_price[$counter];
                    $inventory->selling_price = $request->selling_price[$counter];
                    $inventory->save();

                    PurchaseOrderPurchaseProduct::create([
                        'purchase_order_id' => $order->id,
                        'product_type_id' => $request->product_type[$counter],
                        'product_brand_id' => $request->product_brand[$counter],
                        'product_color_id' => $request->product_color[$counter] ?? null,
                        'product_model_id' => $product->id,
                        'warehouse_id' => $request->warehouse,
                        'name' => $product->name,
                        'serial_no' => $inventory->serial_no,
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);
                }

                $inventoryLog = new PurchaseInventoryLog();
                $inventoryLog->product_type_id = $request->product_type[$counter];
                $inventoryLog->product_brand_id = $request->product_brand[$counter];
                $inventoryLog->product_color_id =  $request->product_color[$counter] ?? null;
                $inventoryLog->product_model_id = $product->id;
                $inventoryLog->type = 1;
                $inventoryLog->date = $request->date;
                $inventoryLog->serial_no =  $inventory->serial_no;
                $inventoryLog->warehouse_id = $request->warehouse;
                $inventoryLog->quantity = $request->quantity[$counter];
                $inventoryLog->unit_price = $request->unit_price[$counter];
                $inventoryLog->selling_price = $request->selling_price[$counter];
                $inventoryLog->supplier_id = $request->supplier;
                $inventoryLog->purchase_order_id = $order->id;
                $inventoryLog->purchase_inventory_id = $inventory->id;
                $inventoryLog->save();

                $total += $request->quantity[$counter] * $request->unit_price[$counter];

            }else{

//                $productImeis = explode(",", $request->imei_code[$counter]);
                $productImeis = explode(PHP_EOL, $request->imei_code[$counter]);

                foreach ($productImeis as $product_ime) {

                    $inventory = PurchaseInventory::create([
                        'product_type_id' => $request->product_type[$counter],
                        'product_brand_id' => $request->product_brand[$counter],
                        'product_color_id' => $request->product_color[$counter],
                        'product_model_id' => $product->id,
                        'warehouse_id' => $request->warehouse,
                        'serial_no' => trim($product_ime),
                        'quantity' => 1,
                        'last_unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                    ]);

                    PurchaseOrderPurchaseProduct::create([
                        'purchase_order_id' => $order->id,
                        'product_type_id' => $request->product_type[$counter],
                        'product_brand_id' => $request->product_brand[$counter],
                        'product_color_id' => $request->product_color[$counter],
                        'product_model_id' => $product->id,
                        'warehouse_id' => $request->warehouse,
                        'name' => $product->name,
                        'serial_no' => trim($product_ime),
                        'quantity' => 1,
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'total' => $request->unit_price[$counter],
                    ]);

                    PurchaseInventoryLog::create([
                        'product_type_id' => $request->product_type[$counter],
                        'product_brand_id' => $request->product_brand[$counter],
                        'product_color_id' => $request->product_color[$counter],
                        'product_model_id' => $product->id,
                        'type' => 1,
                        'date' => $request->date,
                        'warehouse_id' => $request->warehouse,
                        'quantity' => 1,
                        'serial_no' => trim($product_ime),
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'supplier_id' => $request->supplier,
                        'purchase_order_id' => $order->id,
                        'purchase_inventory_id' => $inventory->id,
                    ]);

                    $imeTotal += $request->unit_price[$counter];

                }
            }

            $counter++;
        }

        if($request->discount_type==1){
            $order->sub_total = $imeTotal;
            $discount = ($imeTotal * $request->discount) / 100;
            $total = $imeTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }else if($request->discount_type==2){
            $order->sub_total = $imeTotal;
            $discount = $request->discount_in_amount;
            $total = $imeTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }else{
            $order->sub_total = $imeTotal;
            $discount = 0;
            $total = $imeTotal - $discount;
            $order->total = $total;
            $due = $total - $request->paid;
            $order->due = $due;
            $order->save();
        }

        if ($request->paid > 0) {
            if ($request->payment_type == 1 || $request->payment_type == 3) {
                $payment = new PurchasePayment();
                $payment->purchase_order_id = $order->id;
                $payment->transaction_method = $request->payment_type;
                $payment->received_type = 1;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->save();

                if ($request->payment_type == 1)
                    Cash::first()->decrement('amount', $request->paid);
                else
                    MobileBanking::first()->decrement('amount', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 2;
                $log->transaction_method = $request->payment_type;
                $log->account_head_type_id = 1;
                $log->account_head_sub_type_id = 1;
                $log->amount = $request->paid;
                $log->purchase_payment_id = $payment->id;
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

                $payment = new PurchasePayment();
                $payment->purchase_order_id = $order->id;
                $payment->transaction_method = 2;
                $payment->received_type = 1;
                $payment->bank_id = $request->bank;
                $payment->branch_id = $request->branch;
                $payment->bank_account_id = $request->account;
                $payment->cheque_no = $request->cheque_no;
                $payment->cheque_image = $image;
                $payment->amount = $request->paid;
                $payment->date = $request->date;
                $payment->save();

                BankAccount::find($request->account)->decrement('balance', $request->paid);

                $log = new TransactionLog();
                $log->date = $request->date;
                $log->particular = 'Payment for '.$order->order_no;
                $log->transaction_type = 2;
                $log->transaction_method = 2;
                $log->account_head_type_id = 1;
                $log->account_head_sub_type_id = 1;
                $log->bank_id = $request->bank;
                $log->branch_id = $request->branch;
                $log->bank_account_id = $request->account;
                $log->cheque_no = $request->cheque_no;
                $log->cheque_image = $image;
                $log->amount = $request->paid;
                $log->purchase_payment_id = $payment->id;
                $log->created_by = Auth::id();
                $log->save();
            }
        }

        // Buying Price log
        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = 'Buying price for '.$order->order_no;
        $log->transaction_type = 4;
        $log->transaction_method = 0;
        $log->account_head_type_id = 5;
        $log->account_head_sub_type_id = 5;
        $log->amount = $imeTotal;
        $log->purchase_payment_id = $order->id;
        $log->created_by = Auth::id();
        $log->save();

        return redirect()->route('purchase_receipt.details', ['order' => $order->id]);
    }

    public function purchaseOrderEditPost(Request $request, $id) {
        $order = PurchaseOrder::where('id', $id)->first();
        $request->validate([
            'supplier' => 'required',
            'warehouse' => 'required',
            'date' => 'required|date',
            'category.*' => 'required',
            'subcategory.*' => 'required',
            'quantity.*' => 'required|numeric|min:.01',
            'unit_price.*' => 'required|numeric|min:0',
            'selling_price.*' => 'required|numeric|min:0',
        ]);

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = PurchaseProduct::find($reqProduct);
            $order_product = PurchaseOrderPurchaseProduct::where(['purchase_order_id'=>$order->id, 'purchase_product_id'=> $reqProduct])->first();
            $inventory = PurchaseInventory::where('purchase_product_id', $product->id)->first();
            if ($inventory && $order_product) {
                $inventory->decrement('quantity', $order_product->quantity);
                $inventory->decrement('total', $order_product->total);

                // Out previuos product
                $inventoryLog = new PurchaseInventoryLog();
                $inventoryLog->purchase_product_id = $product->id;
                $inventoryLog->purchase_product_category_id = $order_product->purchase_product_category_id;
                $inventoryLog->purchase_product_sub_category_id = $order_product->purchase_product_sub_category_id;
                $inventoryLog->type = 2;
                $inventoryLog->date = $request->date;
                $inventoryLog->warehouse_id = $request->warehouse;
                $inventoryLog->quantity = $order_product->quantity;
                $inventoryLog->unit_price = $order_product->unit_price;
                $inventoryLog->selling_price = $order_product->selling_price;
                $inventoryLog->supplier_id = $request->supplier;
                $inventoryLog->save();
            }
            if ($order_product) {
                $order_product->name = $product->name;
                $order_product->purchase_product_category_id = $product->purchase_product_category_id;
                $order_product->purchase_product_sub_category_id = $product->purchase_product_sub_category_id;
                $order_product->serial_no = $product->code;
                $order_product->quantity = $request->quantity[$counter];
                $order_product->unit_price = $request->unit_price[$counter];
                $order_product->selling_price = $request->selling_price[$counter];
                $order_product->total = $request->quantity[$counter] * $request->unit_price[$counter];
                $order_product->save();
            }

            $total += $request->quantity[$counter] * $request->unit_price[$counter];

            // Inventory
            $inventory = PurchaseInventory::where('purchase_product_id', $product->id)->first();

            $totalPrice = DB::table('purchase_order_purchase_product')
                ->where('purchase_product_id', $product->id)
                ->sum('total');
            $totalQuantity = DB::table('purchase_order_purchase_product')
                ->where('purchase_product_id', $product->id)
                ->sum('quantity');

            $avgPrice = $totalPrice / $totalQuantity;
            // dd($avgPrice);

            if ($inventory) {
                $inventory->purchase_product_id = $product->id;
                $inventory->purchase_product_category_id = $product->purchase_product_category_id;
                $inventory->purchase_product_sub_category_id = $product->purchase_product_sub_category_id;
                $inventory->last_unit_price = $request->unit_price[$counter];
                $inventory->selling_price = $request->selling_price[$counter];
                $inventory->avg_unit_price = $avgPrice;
                $inventory->warehouse_id = $request->warehouse;
                $inventory->save();
                $invent_total = $request->quantity[$counter] * $request->unit_price[$counter];
                $inventory->increment('quantity',$request->quantity[$counter]);
                $inventory->increment('total',$invent_total);
            }

            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->purchase_product_id = $product->id;
            $inventoryLog->purchase_product_category_id = $product->purchase_product_category_id;
            $inventoryLog->purchase_product_sub_category_id = $product->purchase_product_sub_category_id;
            $inventoryLog->type = 1;
            $inventoryLog->date = $request->date;
            $inventoryLog->warehouse_id = $request->warehouse;
            $inventoryLog->quantity = $request->quantity[$counter];
            $inventoryLog->unit_price = $request->unit_price[$counter];
            $inventoryLog->selling_price = $request->selling_price[$counter];
            $inventoryLog->supplier_id = $request->supplier;
            $inventoryLog->save();

            $counter++;
        }
        $order->supplier_id = $request->supplier;
        $order->warehouse_id = $request->warehouse;
        $order->date = $request->date;
        $order->total = $total;
        $order->due = $order->total - $order->paid;
        $order->save();


        return redirect()->route('purchase_receipt.qr_code', ['order' => $order->id]);
    }

    public function purchaseReceipt() {
        return view('purchase.receipt.all');
    }

    public function purchaseReceiptDetails(PurchaseOrder $order) {
        return view('purchase.receipt.details', compact('order'));
    }

    public function transferReceiptDetails(StockTransferOrder $order) {
        return view('purchase.receipt.transfer_details', compact('order'));
    }

    public function purchaseReceiptPrint(PurchaseOrder $order) {
        return view('purchase.receipt.print', compact('order'));
    }

    public function transferReceiptPrint(StockTransferOrder $order) {
        return view('purchase.receipt.transfer_print', compact('order'));
    }

    public function qrCode(PurchaseOrder $order) {
        return view('purchase.receipt.qr_code', compact('order'));
    }

    public function qrCodePrint(PurchaseOrder $order) {

        return view('purchase.receipt.qr_code_print', compact('order'));
    }
    public function qrSingleCodePrint($order) {
        $product = PurchaseOrderPurchaseProduct::where('id',$order)->first();

        return view('purchase.receipt.qr_code_print', compact('product'));
    }

    public function supplierPayment() {
        $suppliers = Supplier::all();
        $banks = Bank::where('status', 1)->orderBy('name')->get();

        return view('purchase.supplier_payment.all', compact('suppliers', 'banks'));
    }

    public function supplierPaymentGetOrders(Request $request) {
        $orders = PurchaseOrder::where('supplier_id', $request->supplierId)
            ->where('due', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function supplierPaymentGetRefundOrders(Request $request) {
        $orders = PurchaseOrder::where('supplier_id', $request->supplierId)
            ->where('refund', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function supplierPaymentOrderDetails(Request $request) {
        $order = PurchaseOrder::where('id', $request->orderId)
            ->first()->toArray();

        return response()->json($order);
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
            $order = PurchaseOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;
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

        $order = PurchaseOrder::find($request->order);

        if ($request->payment_type == 1 || $request->payment_type == 3) {
            $payment = new PurchasePayment();
            $payment->purchase_order_id = $order->id;
            $payment->transaction_method = $request->payment_type;
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
            $log->particular = 'Paid to '.$order->supplier->name.' for '.$order->order_no;
            $log->transaction_type = 2;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 1;
            $log->account_head_sub_type_id = 1;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->purchase_payment_id = $payment->id;
            $log->save();

        } else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/purchase_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/purchase_payment_cheque/'.$filename;
            }

            $payment = new PurchasePayment();
            $payment->purchase_order_id = $order->id;
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

            BankAccount::find($request->account)->decrement('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Paid to '.$order->supplier->name.' for '.$order->order_no;
            $log->transaction_type = 2;
            $log->transaction_method = 2;
            $log->account_head_type_id = 1;
            $log->account_head_sub_type_id = 1;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->purchase_payment_id = $payment->id;
            $log->save();
        }

        $order->increment('paid', $request->amount);
        $order->decrement('due', $request->amount);

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('purchase_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function makeRefund(Request $request) {
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
            $order = PurchaseOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:'.$order->refund;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = PurchaseOrder::find($request->order);

        if ($request->payment_type == 1 || $request->payment_type == 3) {
            $payment = new PurchasePayment();
            $payment->purchase_order_id = $order->id;
            $payment->type = 2;
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
            $log->particular = 'Refund from '.$order->supplier->name.' for '.$order->order_no;
            $log->transaction_type = 5;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 7;
            $log->account_head_sub_type_id = 7;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->purchase_payment_id = $payment->id;
            $log->save();

        } else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/purchase_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/purchase_payment_cheque/'.$filename;
            }

            $payment = new PurchasePayment();
            $payment->purchase_order_id = $order->id;
            $payment->type = 2;
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
            $log->particular = 'Refund from '.$order->supplier->name.' for '.$order->order_no;
            $log->transaction_type = 5;
            $log->transaction_method = 2;
            $log->account_head_type_id = 7;
            $log->account_head_sub_type_id = 7;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->purchase_payment_id = $payment->id;
            $log->save();
        }

        $order->decrement('refund', $request->amount);

        return response()->json(['success' => true, 'message' => 'Refund has been completed.', 'redirect_url' => route('purchase_receipt.payment_details', ['payment' => $payment->id])]);
    }

    public function purchasePaymentDetails(PurchasePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('purchase.receipt.payment_details', compact('payment'));
    }

    public function purchasePaymentPrint(PurchasePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('purchase.receipt.payment_print', compact('payment'));
    }

    public function purchaseInventory() {
        return view('purchase.inventory.all');
    }
    public function shopPurchaseInventory() {
        return view('purchase.shop_inventory.all');
    }

    public function purchaseInventorySummary(){
        return view('purchase.inventory.summary');
    }

    public function purchaseInventoryDetails(ProductModel $product, Warehouse $warehouse) {
        return view('purchase.inventory.details', compact('product', 'warehouse'));
    }

    public function purchaseInventoryQrCode(PurchaseProduct $product, Warehouse $warehouse) {
        $rows = PurchaseInventory::where('purchase_product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->where('quantity', '>', 0)->get();

        return view('purchase.inventory.qr_code', compact('rows', 'product', 'warehouse'));
    }


    public function purchaseReceiptEdit(PurchaseOrder $order) {
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $categories= PurchaseProductCategory::where('status', 1)->orderBy('name')->get();

        return view('purchase.receipt.edit', compact('order', 'suppliers',
            'warehouses', 'categories'));
    }

    public function purchaseReceiptEditPost(PurchaseOrder $order, Request $request) {

        $validator = Validator::make($request->all(), [
            'supplier' => ['required'],
            'warehouse' => ['required'],
            'date' => ['required', 'date'],
            'category' => ['required'],
            'subcategory' => ['required'],
            'product' => ['required'],
            'quantity.*' => ['required', 'numeric', 'min:.01'],
            'unit_price.*' => ['required', 'numeric', 'min:0'],
            'selling_price.*' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $previousSerials = [];

        foreach ($order->products as $product){
            $previousSerials[] = $product->pivot->serial_no;
        }

        $counter = 0;
        $total = 0;
        foreach ($request->serial as $serial) {

            if (in_array($serial, $previousSerials)) {

                // Old Item
                $product = PurchaseProduct::find($request->product[$counter]);

                $purchaseProduct = DB::table('purchase_order_purchase_product')
                    ->where('purchase_order_id',$order->id)
                    ->where('serial_no', $serial)->first();

                DB::table('purchase_order_purchase_product')
                    ->where('purchase_order_id',$order->id)
                    ->where('serial_no', $serial)
                    ->update([
                        'purchase_product_id' => $request->product[$counter],
                        'purchase_product_category_id' => $request->category[$counter],
                        'purchase_product_sub_category_id' => $request->subcategory[$counter],
                        'name' => $product->name,
                        'size' => $request->size[$counter],
                        'description' => $request->description[$counter],
                        'serial_no' => $request->serial[$counter],
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);


                $total += $request->quantity[$counter] * $request->unit_price[$counter];

                // Inventory
                $totalPrice = DB::table('purchase_order_purchase_product')
                    ->where('purchase_product_id', $product->id)
                    ->sum('total');
                $totalQuantity = DB::table('purchase_order_purchase_product')
                    ->where('purchase_product_id', $product->id)
                    ->sum('quantity');

                $avgPrice = $totalPrice / $totalQuantity;

                $inventory = PurchaseInventory::where('serial_no', $serial)->first();
                $inventory->purchase_product_id = $product->id;
                $inventory->quantity = $request->quantity[$counter];
                $inventory->last_unit_price = $request->unit_price[$counter];
                $inventory->selling_price = $request->selling_price[$counter];
                $inventory->avg_unit_price = $avgPrice;
                $inventory->total = $totalPrice;
                $inventory->warehouse_id = $request->warehouse;
                $inventory->save();

                if ($request->quantity[$counter] != $purchaseProduct->quantity) {
                    $inventoryLog = new PurchaseInventoryLog();
                    $inventoryLog->purchase_product_id = $product->id;

                    if ($request->quantity[$counter] > $purchaseProduct->quantity) {

                        $inventoryLog->type = 3;
                        $inventoryLog->quantity = $request->quantity[$counter] - $purchaseProduct->quantity;
                    } else {

                        $inventoryLog->type = 4;
                        $inventoryLog->quantity = $purchaseProduct->quantity - $request->quantity[$counter];
                    }

                    $inventoryLog->date = date('Y-m-d');
                    $inventoryLog->warehouse_id = $request->warehouse;
                    $inventoryLog->unit_price = $request->unit_price[$counter];
                    $inventoryLog->supplier_id = $request->supplier;
                    $inventoryLog->sales_order_id = $order->id;
                    $inventoryLog->save();
                }

                if (($key = array_search($serial, $previousSerials)) !== false) {
                    unset($previousSerials[$key]);
                }
            } else {
                // New Item
                $product = PurchaseProduct::find($request->product[$counter]);

                $product_names = explode(" ", $product->name);
                $descriptions = explode(" ", $request->description[$counter]);
                $description = "";
                $acronym = "";

                foreach ($product_names as $w) {
                    $acronym .= $w[0];
                }
                foreach ($descriptions as $des) {
                    $description .= $des[0];
                }

                $productCode = $acronym.$description.rand(10000, 99999);

                $total += $request->quantity[$counter] * $request->unit_price[$counter];

                // Inventory
                $exist = PurchaseInventory::where('purchase_product_id', $product->id)
                    ->where('size',$request->size[$counter])
                    ->where('description',$request->description[$counter])
                    ->first();
//                $totalPrice = DB::table('purchase_order_purchase_product')
//                    ->where('purchase_product_id', $product->id)
//                    ->where('size',$request->size[$counter])
//                    ->where('description',$request->description[$counter])
//                    ->sum('total');
//                $totalQuantity = DB::table('purchase_order_purchase_product')
//                    ->where('purchase_product_id', $product->id)
//                    ->where('size',$request->size[$counter])
//                    ->where('description',$request->description[$counter])
//                    ->sum('quantity');
//
//                $avgPrice = $totalPrice / $totalQuantity;

                if ($exist) {
                    $inventory = PurchaseInventory::where('purchase_product_id',$product->id)
                        ->where('size',$request->size[$counter])
                        ->where('description',$request->description[$counter])
                        ->first();
                    $inventory->purchase_product_id = $product->id;
                    $inventory->purchase_product_category_id = $product->purchase_product_category_id;
                    $inventory->purchase_product_sub_category_id = $product->purchase_product_sub_category_id;
                    $inventory->size = $request->size[$counter];
                    $inventory->description = $request->description[$counter];
                    $inventory->last_unit_price = $request->unit_price[$counter];
                    $inventory->selling_price = $request->selling_price[$counter];
                    //$inventory->avg_unit_price = $avgPrice;
                    $inventory->warehouse_id = $request->warehouse;
                    $inventory->save();
                    $invent_total = $request->quantity[$counter] * $request->unit_price[$counter];
                    $inventory->increment('quantity',$request->quantity[$counter]);
                    $inventory->increment('total',$invent_total);

                    $order->products()->attach($product->id, [
                        'purchase_product_category_id' => $request->category[$counter],
                        'purchase_product_sub_category_id' => $request->subcategory[$counter],
                        'name' => $product->name,
                        'serial_no' => $inventory->serial_no,
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);
                }else {
                    $inventory = new PurchaseInventory();
                    $inventory->purchase_product_id = $product->id;
                    $inventory->purchase_product_category_id = $product->purchase_product_category_id;
                    $inventory->purchase_product_sub_category_id = $product->purchase_product_sub_category_id;
                    $inventory->size = $request->size[$counter];
                    $inventory->description = $request->description[$counter];
                    $inventory->quantity = $request->quantity[$counter];
                    $inventory->serial_no = $productCode;
                    $inventory->last_unit_price = $request->unit_price[$counter];
                    $inventory->selling_price = $request->selling_price[$counter];
                    //$inventory->avg_unit_price = $avgPrice;
                    $inventory->total = $request->quantity[$counter] * $request->unit_price[$counter];
                    $inventory->warehouse_id = $request->warehouse;
                    $inventory->save();

                    $order->products()->attach($product->id, [
                        'purchase_product_category_id' => $request->category[$counter],
                        'purchase_product_sub_category_id' => $request->subcategory[$counter],
                        'name' => $product->name,
                        'serial_no' => $productCode,
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'selling_price' => $request->selling_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);
                }

                $inventoryLog = new PurchaseInventoryLog();
                $inventoryLog->purchase_product_id = $product->id;
                $inventoryLog->purchase_product_category_id = $product->purchase_product_category_id;
                $inventoryLog->purchase_product_sub_category_id = $product->purchase_product_sub_category_id;
                $inventoryLog->size = $request->size[$counter];
                $inventoryLog->description = $request->description[$counter];
                $inventoryLog->type = 3;
                $inventoryLog->date = date('Y-m-d');
                $inventoryLog->warehouse_id = $request->warehouse;
                $inventoryLog->quantity = $request->quantity[$counter];
                $inventoryLog->unit_price = $request->unit_price[$counter];
                $inventoryLog->selling_price = $request->selling_price[$counter];
                $inventoryLog->supplier_id = $request->supplier;
                $inventoryLog->save();
            }

            $counter++;
        }

        // Delete items
        foreach ($previousSerials as $serial) {
            $purchaseProduct = DB::table('purchase_order_purchase_product')->where('purchase_order_id', $order->id)
                ->where('serial_no', $serial)->first();

            $inventory = PurchaseInventory::where('serial_no', $serial)
                ->where('purchase_product_id', $purchaseProduct->purchase_product_id)->first();

            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->purchase_product_id = $purchaseProduct->purchase_product_id;
            $inventoryLog->purchase_product_category_id = $purchaseProduct->purchase_product_category_id;
            $inventoryLog->purchase_product_sub_category_id = $purchaseProduct->purchase_product_sub_category_id;
            $inventoryLog->type = 4;
            $inventoryLog->quantity = $purchaseProduct->quantity;
            $inventoryLog->date = date('Y-m-d');
            $inventoryLog->warehouse_id = $request->warehouse;
            $inventoryLog->unit_price = $purchaseProduct->unit_price;
            $inventoryLog->selling_price = $purchaseProduct->selling_price;
            $inventoryLog->supplier_id = $request->supplier;
            $inventoryLog->sales_order_id = $order->id;
            $inventoryLog->save();

            $inventory->delete();
            DB::table('purchase_order_purchase_product')->where('purchase_order_id', $order->id)
                ->where('serial_no', $serial)->delete();
        }

        // Update Order
        $order->supplier_id = $request->supplier;
        $order->warehouse_id = $request->warehouse;
        $order->date = $request->date;

        if ($total > $order->total) {
            if ($order->refund > 0) {
                if ($order->refund > $total - $order->total) {
                    $order->decrement('refund', $total - $order->total);
                } else  {
                    $previousRefund = $order->refund;
                    $order->decrement('refund', $order->refund);
                    $order->increment('due', $total - $order->total- $previousRefund);
                }
            } else {
                $order->increment('due', $total - $order->total);
            }

        } elseif($order->total > $total) {
            if ($order->due >= 0) {
                if ($order->due > $order->total - $total) {
                    $order->decrement('due', $order->total - $total);
                } else {
                    $previousDue = $order->due;
                    $order->decrement('due', $order->due);
                    $order->increment('refund', $order->total - $total - $previousDue);
                }
            } else {
                $order->increment('refund', $order->total - $total);
            }
        }

        $order->total = $total;
        $order->save();


        return redirect()->route('purchase_receipt.details', ['order' => $order->id]);
    }

    public function checkDeleteStatus(Request $request) {
        $inventory = PurchaseInventory::where('serial_no', $request->serial)->first();

        if ($inventory) {
            if ($inventory->quantity > 0) {
                return response()->json(['success' => true, 'message' => 'This product not sold.']);
            } else {
                return response()->json(['success' => false, 'message' => 'This product already sold.']);
            }
        }

        return response()->json(['success' => true, 'message' => 'Serial not found.']);
    }

    public function purchaseProductJson(Request $request) {
        if (!$request->searchTerm) {
            $products = PurchaseProduct::where('status', 1)->orderBy('name')->limit(10)->get();
        } else {
            $products = PurchaseProduct::where('status', 1)->where('name', 'like', '%'.$request->searchTerm.'%')->orderBy('name')->limit(10)->get();
        }

        $data = array();

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'text' => $product->name
            ];
        }

        echo json_encode($data);
    }

    public function purchaseReceiptDatatable() {
        $query = PurchaseOrder::with('supplier','warehouse');

        return DataTables::eloquent($query)
            ->addColumn('supplier', function(PurchaseOrder $order) {
                return $order->supplier->name;
            })
            ->addColumn('warehouse', function(PurchaseOrder $order) {
                return $order->warehouse->name;
            })
            ->addColumn('action', function(PurchaseOrder $order) {
                $btn = '<a href="'.route('purchase_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('purchase_receipt.qr_code', ['order' => $order->id]).'" class="btn btn-success btn-sm">BarCode</a> ';
//                $btn .= '<a href="'.route('purchase_receipt.edit', $order->id).'" class="btn btn-primary btn-sm">Edit</a>';
                return $btn;
            })
            ->addColumn('products_code', function(PurchaseOrder $order) {
//                $products = '';
                $count =1;
                foreach ($order->order_products as $key => $product) {
//                    $products .= $product->serial_no??'';

                    if(!empty($order->order_products[$key+1])){
//                        $products .= ', ';
                        $count++;
                    }
                }
                return $count;
            })
            ->filterColumn('products_code', function ($query, $keyword) {
                // $order_products = PurchaseProduct::where('code','like', '%'.$keyword.'%')->pluck('id');
                $order_ids = PurchaseOrderPurchaseProduct::where('serial_no','like', '%'.$keyword.'%')->distinct('purchase_order_id')->pluck('purchase_order_id');
                return $query->whereIn('id', $order_ids);
            })
            ->editColumn('date', function(PurchaseOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->editColumn('total', function(PurchaseOrder $order) {
                return '৳'.number_format($order->total, 2);
            })
            ->editColumn('paid', function(PurchaseOrder $order) {
                return '৳'.number_format($order->paid, 2);
            })
            ->editColumn('due', function(PurchaseOrder $order) {
                return '৳'.number_format($order->due, 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function transferReceiptDatatable() {
        $query = StockTransferOrder::with('PurchaseInventory');

        return DataTables::eloquent($query)

            ->addColumn('action', function(StockTransferOrder $order) {
                $btn = '<a href="'.route('transfer_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a>';
//                <a href="'.route('transfer_receipt.qr_code', ['order' => $order->id]).'" class="btn btn-success btn-sm">BarCode</a>
//                $btn .= '<a href="'.route('purchase_receipt.edit', $order->id).'" class="btn btn-primary btn-sm">Edit</a>';
                return $btn;
            })
            ->addColumn('products_code', function(StockTransferOrder $order) {
//                $products = '';
                $count = 1;
                foreach ($order->PurchaseInventory as $key => $product) {
//                    $products .= $product->serial_no??'';
                    if(!empty($order->PurchaseInventory[$key+1])){
                        $count++;
                    }
                }
                return $count;
            })

            ->editColumn('date', function(StockTransferOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function purchaseInventoryDatatable() {
        $query = PurchaseInventory::with('productModel','warehouse','brand')->where('shop_id',0)->where('quantity','>',0);
        //->select(DB::raw('sum(`quantity`) as quantity, sum(`quantity` * `selling_price`) as total_selling_price,sum(`quantity` * `last_unit_price`) as total_last_unit_price,sum(`quantity` * `avg_unit_price`) as total_avg_unit_price, purchase_product_id, warehouse_id'));
        //->select(DB::raw('sum(`quantity`) as quantity, sum(`quantity` * `selling_price`) as total_selling_price, purchase_product_id, warehouse_id'));

        return DataTables::eloquent($query)
//            ->addColumn('total_purchase_qty', function (PurchaseInventory $inventory) {
//                return $inventory->purchaseQty($inventory->purchase_product_id,$inventory->warehouse_id);
//            })
//            ->addColumn('total_sell_qty', function (PurchaseInventory $inventory) {
//                return $inventory->saleQty($inventory->purchase_product_id,$inventory->warehouse_id);
//            })
            ->addColumn('model_no', function(PurchaseInventory $inventory) {
                return $inventory->productModel->name ?? '';
            })
            ->addColumn('brand_name', function(PurchaseInventory $inventory) {
                return $inventory->brand->name ?? '';
            })
//            ->addColumn('category', function(PurchaseInventory $inventory) {
//                return $inventory->product->category->name;
//            })
//            ->addColumn('subcategory', function(PurchaseInventory $inventory) {
//                return $inventory->product->subcategory->name;
//            })
            ->addColumn('warehouse', function(PurchaseInventory $inventory) {
                return $inventory->warehouse->name;
            })
            ->addColumn('action', function(PurchaseInventory $inventory) {
//                return '<a href="'.route('purchase_inventory.details', ['product' => $inventory->product_model_id, 'warehouse' => $inventory->warehouse->id]).'" class="btn btn-primary btn-sm">Details</a> <a href="'.route('purchase_inventory.qr_code', ['product' => $inventory->product_model_id, 'warehouse' => $inventory->warehouse->id]).'" class="btn btn-success btn-sm">Bar Code</a>';
                return '<a href="'.route('purchase_inventory.details', ['product' => $inventory->product_model_id, 'warehouse' => $inventory->warehouse->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('quantity', function(PurchaseInventory $inventory) {
                return number_format($inventory->quantity, 2);
            })
            ->editColumn('total', function(PurchaseInventory $inventory) {
                return number_format($inventory->total, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function shopPurchaseInventoryDatatable() {
        $query = PurchaseInventory::with('productModel','warehouse','brand','shop')->where('shop_id',1)->where('quantity','>',0);

        // ->groupBy('purchase_product_id', 'warehouse_id')
        //->select(DB::raw('sum(`quantity`) as quantity, sum(`quantity` * `selling_price`) as total_selling_price,sum(`quantity` * `last_unit_price`) as total_last_unit_price,sum(`quantity` * `avg_unit_price`) as total_avg_unit_price, purchase_product_id, warehouse_id'));
        //->select(DB::raw('sum(`quantity`) as quantity, sum(`quantity` * `selling_price`) as total_selling_price, purchase_product_id, warehouse_id'));

        return DataTables::eloquent($query)
//            ->addColumn('total_purchase_qty', function (PurchaseInventory $inventory) {
//                return $inventory->purchaseQty($inventory->purchase_product_id,$inventory->warehouse_id);
//            })
//            ->addColumn('total_sell_qty', function (PurchaseInventory $inventory) {
//                return $inventory->saleQty($inventory->purchase_product_id,$inventory->warehouse_id);
//            })
            ->addColumn('model_no', function(PurchaseInventory $inventory) {
                return $inventory->productModel->name ?? '';
            })
            ->addColumn('brand_name', function(PurchaseInventory $inventory) {
                return $inventory->brand->name ?? '';
            })
//            ->addColumn('category', function(PurchaseInventory $inventory) {
//                return $inventory->product->category->name;
//            })
//            ->addColumn('subcategory', function(PurchaseInventory $inventory) {
//                return $inventory->product->subcategory->name;
//            })
            ->addColumn('shop', function(PurchaseInventory $inventory) {
                return $inventory->shop->name;
            })
            ->addColumn('action', function(PurchaseInventory $inventory) {
//                return '<a href="'.route('purchase_inventory.details', ['product' => $inventory->product_model_id, 'warehouse' => $inventory->warehouse->id]).'" class="btn btn-primary btn-sm">Details</a> <a href="'.route('purchase_inventory.qr_code', ['product' => $inventory->product_model_id, 'warehouse' => $inventory->warehouse->id]).'" class="btn btn-success btn-sm">Bar Code</a>';
                return '<a href="'.route('purchase_inventory.details', ['product' => $inventory->product_model_id, 'warehouse' => $inventory->warehouse->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('quantity', function(PurchaseInventory $inventory) {
                return number_format($inventory->quantity, 2);
            })
            ->editColumn('selling_price', function(PurchaseInventory $inventory) {
                return number_format($inventory->selling_price, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }


    public function purchaseInventorySummaryDatatable()
    {
        $query = PurchaseInventory::with('category', 'subcategory', 'warehouse')
            ->select(DB::raw('sum(quantity) as quantity,sum(`quantity` * `last_unit_price`) as total_last_unit_price,purchase_product_category_id,purchase_product_sub_category_id,warehouse_id'))
            ->groupBy('purchase_product_category_id','purchase_product_sub_category_id','warehouse_id');

        return DataTables::eloquent($query)

            ->addColumn('custom_last_price', function (PurchaseInventory $inventory) {
                return $inventory->custom_last_price($inventory->purchase_product_category_id,$inventory->purchase_product_sub_category_id,$inventory->warehouse_id);
            })
            ->addColumn('total_purchase_qty', function (PurchaseInventory $inventory) {
                return $inventory->purchaseQty($inventory->purchase_product_category_id,$inventory->purchase_product_sub_category_id,$inventory->warehouse_id);
            })
            ->addColumn('total_sell_qty', function (PurchaseInventory $inventory) {
                return $inventory->saleQty($inventory->purchase_product_category_id,$inventory->purchase_product_sub_category_id,$inventory->warehouse_id);
            })
            ->editColumn('category', function (PurchaseInventory $inventory) {
                return $inventory->category->name ?? '';
            })
            ->editColumn('subcategory', function (PurchaseInventory $inventory) {
                return $inventory->subcategory->name ?? '';
            })
            ->editColumn('warehouse', function (PurchaseInventory $inventory) {
                return $inventory->warehouse->name ?? '';
            })

            ->editColumn('quantity', function (PurchaseInventory $inventory) {
                return number_format($inventory->quantity, 2);
            })
            ->editColumn('total', function (PurchaseInventory $inventory) {
                return number_format($inventory->total, 2);
            })
            // ->rawColumns(['action'])
            ->toJson();
    }

    public function purchaseInventoryDetailsDatatable() {
        $query = PurchaseInventoryLog::where('product_model_id', request('product_id'))
            //->where('warehouse_id', request('warehouse_id'))
            ->with('product', 'supplier', 'order');

        return DataTables::eloquent($query)
            ->editColumn('date', function(PurchaseInventoryLog $log) {
                return $log->date->format('j F, Y');
            })
            ->editColumn('type', function(PurchaseInventoryLog $log) {
                if ($log->type == 1 && $log->shop_id == 0)
                    return '<span class="label label-success">Godown In</span>';
                elseif ($log->type == 1 && $log->shop_id == 1)
                    return '<span class="label label-danger">Godown Out Shop In</span>';
                elseif ($log->type == 2 && $log->shop_id == 1)
                    return '<span class="label label-warning ">Sell from shop</span>';
                elseif ($log->type == 3)
                    return '<span class="label label-success">Add</span>';
                else
                    return '<span class="label label-danger">Return</span>';
            })
            ->editColumn('quantity', function(PurchaseInventoryLog $log) {
                return number_format($log->quantity, 2);
            })
//            ->editColumn('avg_unit_price', function(PurchaseInventoryLog $log) {
//                return number_format($log->avg_unit_price, 2);
//            })
            ->editColumn('selling_price', function(PurchaseInventoryLog $log) {
                return number_format($log->selling_price, 2);
            })
            ->editColumn('last_unit_price', function(PurchaseInventoryLog $log) {
                return number_format($log->last_unit_price, 2);
            })
            ->editColumn('total', function(PurchaseInventoryLog $log) {
                return number_format($log->total, 2);
            })
            ->editColumn('unit_price', function(PurchaseInventoryLog $log) {
                if ($log->unit_price)
                    return '৳'.number_format($log->unit_price, 2);
                else
                    return '';
            })
            ->editColumn('supplier', function(PurchaseInventoryLog $log) {
                if ($log->supplier)
                    return $log->supplier->name;
                else
                    return '';
            })
            ->editColumn('order', function(PurchaseInventoryLog $log) {
                if ($log->order)
                    return '<a href="'.route('sale_receipt.details', ['order' => $log->order->id]).'">'.$log->order->order_no.'</a>';
                else
                    return '';
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['type', 'order'])
            ->filter(function ($query) {
                if (request()->has('date') && request('date') != '') {
                    $dates = explode(' - ', request('date'));
                    if (count($dates) == 2) {
                        $query->where('date', '>=', $dates[0]);
                        $query->where('date', '<=', $dates[1]);
                    }
                }

                if (request()->has('type') && request('type') != '') {
                    $query->where('type', request('type'));
                }
            })
            ->toJson();
    }

    public function stockTransferInvoice(){
        return view('purchase.inventory.transfer_invoice');
    }

    public function purchaseStockTransfer()
    {
        $source_warehouses = Warehouse::where('status',1)->get();
        $target_shops = Shop::where('status',1)->orderBy('id','desc')->get();
//        $products = PurchaseInventory::where('quantity', '>', 0)->get();

        return view('purchase.inventory.stock_transfer', compact('target_shops','source_warehouses'));
    }

    public function transferProductDetails(Request $request) {

        if (auth()->user()->role == 1){
            $product = PurchaseInventory::with('productModel','color','brand','productType')
                ->where('serial_no', $request->imeiNo)
                ->where('quantity', '>', 0)
                ->where('shop_id', '!=', 1)
                ->orWhereHas('productModel', function($q) use ($request){
                    $q->where('name', $request->imeiNo);
                })
                ->first();
            if ($product) {
                $product = $product->toArray();
                //dd($product);
                return response()->json(['success' => true, 'data' => $product, 'count' => $product['quantity']]);
            } else {
                return response()->json(['success' => false, 'message' => 'Not found.']);
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
                    ->where('shop_id', '!=', 1)
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
                return response()->json(['success' => false, 'message' => 'Not found.']);
            }


        }

    }

    public function purchaseStockTransferPost(Request $request)
    {

        $request->validate([
            'source_warehouse' => 'required',
            'target_shop' => 'required',
            'imei.*' => 'required',
            'transfer_quantity.*' => 'required|min:1|numeric',
        ]);


//        $source_warehouse = Warehouse::find($request->source_warehouse);
//        $target_shop = Shop::find($request->$target_shop);
//
//        if ($source_warehouse->id == $target_shop->id) {
////            $message = 'Source Warehouse And Target Warehouse Not to be Same';
//            return redirect()->back()->withInput()->with('message', 'Source Warehouse And Target S Not to be Same.');
//        }
        $available = true;
        $message = '';
        $counter = 0;



        if($request->product_model) {
            foreach ($request->product_model as $productId) {
                $inventory = PurchaseInventory::where('serial_no',$request->imei[$counter])
                    ->where('warehouse_id', $request->source_warehouse)
                    ->first();

                if ($inventory) {
                    if ($request->transfer_quantity[$counter] > $inventory->quantity) {
                        $available = false;
                        break;
                    }
                    $counter++;
                }
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', 'Insufficient'.$inventory->product->name);
        }


        $order = new StockTransferOrder();
        $order->sourch_warehouse_id = $request->source_warehouse;
        $order->target_shop_id = $request->target_shop;
        $order->date = date('Y-m-d');
        $order->user_id = Auth::id();
        $order->save();
        $order->order_no = str_pad($order->id, 5, 0, STR_PAD_LEFT);
        $order->save();

        $counter = 0;

        if($request->product_model) {

            foreach ($request->product_model as $reqProduct) {

                $sourceInventory = PurchaseInventory::where('serial_no',$request->imei[$counter])
                    ->where('warehouse_id', $request->source_warehouse)
                    ->first();

                if ($sourceInventory) {
                    $sourceInventory->decrement('quantity',$request->transfer_quantity[$counter]);

                    PurchaseInventory::create([
                        'product_type_id' => $sourceInventory->product_type_id,
                        'product_brand_id' => $sourceInventory->product_brand_id,
                        'product_color_id' => $sourceInventory->product_color_id ?? null,
                        'product_model_id' => $sourceInventory->product_model_id ?? null,
                        'warehouse_id' => $sourceInventory->warehouse_id,
                        'shop_id' => $request->target_shop,
                        'stock_transfer_order_id' => $order->id,
                        'serial_no' => $sourceInventory->serial_no,
                        'quantity' => $request->transfer_quantity[$counter],
                        'last_unit_price' => $sourceInventory->last_unit_price,
                        'selling_price' => $sourceInventory->selling_price,
                    ]);

                    $purchaseInventoryLog = new PurchaseInventoryLog();
                    $purchaseInventoryLog->stock_transfer_order_id = $order->id;
                    $purchaseInventoryLog->serial_no = $sourceInventory->serial_no;
                    $purchaseInventoryLog->product_type_id = $sourceInventory->product_type_id;
                    $purchaseInventoryLog->product_brand_id = $sourceInventory->product_brand_id;
                    $purchaseInventoryLog->product_model_id = $sourceInventory->product_model_id;
                    $purchaseInventoryLog->product_color_id = $sourceInventory->product_color_id ?? null;
                    $purchaseInventoryLog->type = 1;
                    $purchaseInventoryLog->date = date('Y-m-d');
                    $purchaseInventoryLog->warehouse_id = $sourceInventory->warehouse_id;
                    $purchaseInventoryLog->shop_id = $request->target_shop;
                    $purchaseInventoryLog->quantity = $request->transfer_quantity[$counter];
                    $purchaseInventoryLog->unit_price = $sourceInventory->unit_price;
                    $purchaseInventoryLog->selling_price = $sourceInventory->selling_price;
                    $purchaseInventoryLog->purchase_order_id = $sourceInventory->id;
                    $purchaseInventoryLog->user_id = Auth::id();
                    $purchaseInventoryLog->stock_transfer_status = 1;
                    $purchaseInventoryLog->save();

                }
                $counter++;
            }
        }
        return redirect()->route('transfer_receipt.details',['order'=>$order->id])->with('message', 'Transfer Successful .');


    }
}
