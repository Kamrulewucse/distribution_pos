<?php

namespace App\Http\Controllers;



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
use App\Models\TransactionLog;
use App\Models\Warehouse;
use App\Models\Wastage;
use App\Models\WastageProduct;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;

class WastageController extends Controller
{
    public function wastage()
    {
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('wastage.create', compact(
            'warehouses',
            'banks',
            'customers'
        ));
    }

    public function wastagePost(Request $request)
    {
        // dd($request->all());
        $total = $request->total;

        $rules = [
            'date' => 'required|date',
        ];
        if ($request->serial) {
            $rules['serial.*'] = 'required';
            $rules['product_name.*'] = 'required';
            $rules['category_name.*'] = 'required';
            $rules['subcategory_name.*'] = 'required';
            $rules['quantity.*'] = 'required|numeric|min:.01';
            $rules['unit_price.*'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $available = true;
        $message = '';
        $counter = 0;

        if ($request->serial) {
            foreach ($request->serial as $serial) {
                $inventory = PurchaseInventory::where('serial_no', $request->serial[$counter])
                    //->where('warehouse_id', $request->warehouse)
                    ->first();

                if ($request->quantity[$counter] > $inventory->quantity) {
                    $available = false;
                    $message = 'Insufficient ' . $inventory->product->name;
                    break;
                }
                $counter++;
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $wastage = new Wastage();
        $wastage->date = date('Y-m-d', strtotime($request->date));
        $wastage->total = $total;
        $wastage->created_by = Auth::user()->id;
        $wastage->save();

        $counter = 0;
        $subTotal = 0;
        $buyingPrice = 0;

        if ($request->serial) {
            foreach ($request->serial as $serial) {
                $inventory = PurchaseInventory::where('serial_no', $request->serial[$counter])
                    //->where('warehouse_id', $request->warehouse)
                    ->with('product')
                    ->with('category')
                    ->with('subcategory')
                    ->first();

                $buyingPrice += $inventory->avg_unit_price * $request->quantity[$counter];

                WastageProduct::create([
                    'wastage_id' => $wastage->id,
                    'purchase_product_id' => $inventory->product->id,
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
                $inventoryLog->purchase_product_category_id = $inventory->category->id;
                $inventoryLog->purchase_product_sub_category_id = $inventory->subcategory->id;
                $inventoryLog->type = 3;
                $inventoryLog->date = $request->date;
                //$inventoryLog->warehouse_id = $request->warehouse;
                $inventoryLog->quantity = $request->quantity[$counter];
                $inventoryLog->unit_price = $request->unit_price[$counter];
                $inventoryLog->sales_order_id = $wastage->id;
                $inventoryLog->save();

                $subTotal += $request->quantity[$counter] * $request->unit_price[$counter];
                $counter++;
            }
        }

        $counter = 0;

        $wastage->total = $subTotal;
        $wastage->save();

        // Buying Price log
        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = 'Wastage for ' . $wastage->id;
        $log->transaction_type = 5;
        $log->transaction_method = 0;
        $log->account_head_type_id = 6;
        $log->account_head_sub_type_id = 6;
        $log->amount = $buyingPrice;
        $log->wastage_id = $wastage->id;
        $log->save();

        return redirect()->route('wastage.details', $wastage->id);
    }

    public function wastageProductDetails(Request $request, $id)
    {
        $wastage = Wastage::where('id', $id)
            ->with('product')
            // ->with('category')
            // ->with('subcategory')
            ->first();
        // dd($wastage);
        return view('wastage.details', compact('wastage'));
    }

    public function wastageProductPrint(Request $request, $id)
    {
        $wastage = Wastage::where('id', $id)
            ->with('product')
            // ->with('category')
            // ->with('subcategory')
            ->first();
        // dd($wastage);
        return view('wastage.print', compact('wastage'));
    }

    public function index(Request $request)
    {
        $wastages = Wastage::orderBy('id','desc')->get();
        return view('wastage.all', compact('wastages'));
    }

    public function wastageDatatable(Request $request)
    {
        $query = Wastage::query();
        return DataTables::eloquent($query)
            ->editColumn('date', function($wastage) {
                return date('Y-m-d', strtotime($wastage->date));
            })
            ->addColumn('action', function($wastage) {
                $btn = '<a href="'.route("wastage.details", $wastage->id).'" class="btn btn-primary" target="_blank"> Detail </a>
                                        <a target="_blank" href="'. route("wastage.print", $wastage->id).'" class="btn btn-primary"> Print </a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

}
