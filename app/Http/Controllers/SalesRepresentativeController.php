<?php

namespace App\Http\Controllers;

use App\Enumeration\Role;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use App\Models\ProductType;
use App\Models\SalesRepresentative;
use App\Models\SrProductAssignOrder;
use App\Models\SrProductAssignOrderItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalesRepresentativeController extends Controller
{
    public function index() {
        return view('sale.sale_representative.all');
    }

    public function add() {
        $productBrands = ProductBrand::where('status',1)->get();
        return view('sale.sale_representative.add',compact('productBrands'));
    }

    public function addPost(Request $request) {
        //dd($request->all());
        $request->validate([
            'brand' => 'required',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed'
        ]);

        $sale_representative = new SalesRepresentative();
        $sale_representative->brand = json_encode($request->brand);
        $sale_representative->name = $request->name;
        $sale_representative->mobile_no = $request->mobile_no;
        $sale_representative->address = $request->address;
        $sale_representative->email = $request->email;
        $sale_representative->save();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role = 2;
        $user->sales_representative_id = $sale_representative->id;
        $user->save();
        $permission = [
            '0'=>'sale',
            '1'=>'customer',
            '2'=>'sales_order',
            '3'=>'sale_receipt',
            '4'=>'sale_report',
        ];
        $user->syncPermissions($permission);

        return redirect()->route('sales_representative')->with('message', 'Sale Representative add successfully.');
    }

    public function edit(SalesRepresentative $sale_representative) {
        $productBrands = ProductBrand::where('status',1)->get();
        return view('sale.sale_representative.edit', compact('sale_representative','productBrands'));
    }

    public function editPost(SalesRepresentative $sale_representative, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,id,'.$sale_representative->user->id,
            'username' => 'required|string|max:255|unique:users,id,'.$sale_representative->user->id,
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $sale_representative->brand = json_encode($request->brand);
        $sale_representative->name = $request->name;
        $sale_representative->mobile_no = $request->mobile_no;
        $sale_representative->address = $request->address;
        $sale_representative->email = $request->email;
        $sale_representative->save();

        $user = User::where('sales_representative_id',$sale_representative->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        if ($request->password != '')
            $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('sales_representative')->with('message', 'Sale Representative edit successfully.');
    }

    public function datatable() {
        $query = SalesRepresentative::with('user');

        return DataTables::eloquent($query)
            ->addColumn('action', function(SalesRepresentative $sale_representative) {
                return '<a class="btn btn-info btn-sm" href="'.route('sales_representative.edit', ['sale_representative' => $sale_representative->id]).'">Edit</a>';
            })
            ->addColumn('username', function(SalesRepresentative $sale_representative) {
                return $sale_representative->user->username ?? '';
            })
            ->addColumn('brand', function(SalesRepresentative $sale_representative) {
                return json_decode($sale_representative->brand) ?? '';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function productAssignCreate()
    {
        $srs = SalesRepresentative::all();
        return view('sr_product_assign.create',compact('srs'));
    }

    public function productAssignCreatePost(Request $request)
    {
        //dd($request->all());
        $order = new SrProductAssignOrder();
        $order->sales_representative_id = $request->sr;
        $order->date = $request->date;
        $order->status = 0;
        $order->user_id = Auth::id();
        $order->save();
        $order->order_no = str_pad($order->id, 8, 0, STR_PAD_LEFT);
        $order->save();

        $counter= 0;
        foreach ($request->product_brand as $reqProductBrand){
            $item =  new SrProductAssignOrderItem();
            $item->sr_product_assign_order_id = $order->id;
            $item->product_brand_id = $request->product_brand[$counter];
            $item->product_model_id = $request->product_model[$counter];
            $item->assign_quantity = $request->quantity[$counter];
            $item->sale_quantity = 0;
            $item->save();
            $counter++;

        }
        return redirect()
            ->route('sr_product_assign.details',['order'=>$order->id])
            ->with('message','SR Product Assign Completed');
    }

    public function srProductAssignReceipt()
    {
        return view('sr_product_assign.all');
    }
    public function srProductAssignList()
    {
        return view('sr_product_assign.single_sr_list');
    }
    public function srProductAssignReceiptDetails(SrProductAssignOrder $order)
    {
        return view('sr_product_assign.details',compact('order'));
    }
    public function srProductAssignOrderClose(Request $request) {
        $rules = [
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = SrProductAssignOrder::find($request->order);
        $order->status = 1;
        $order->closing_date = $request->date;
        $order->note = $request->note;
        $order->save();

        foreach ($order->srAssignProductItem as $item){

            if ($item->assign_quantity > 0){
               SrProductAssignOrderItem::where('sr_product_assign_order_id',$order->id)
                   ->where('id',$item->id)
                   ->first()
                ->update([
                    'back_quantity'=>$item->assign_quantity,
                    'assign_quantity'=>0,
                ]);
            }

        }

        return response()->json(['success' => true, 'message' => 'Assign Order has been completed.']);
    }

    public function srProductAssignReceiptDatatable() {
        $query = SrProductAssignOrder::with('sr');

        if (auth()->user()->role == Role::$SR)
            $query->where('sales_representative_id',auth()->user()->sales_representative_id);

        return DataTables::eloquent($query)
            ->addColumn('sr_name', function(SrProductAssignOrder $order) {
                return $order->sr->name ?? '';
            })
            ->addColumn('action', function(SrProductAssignOrder $order) {
                $btn = '<a href="'.route('sr_product_assign.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a>';
                if ($order->status == 0 && auth()->user()->role == Role::$ADMIN)
                    $btn .= ' <a role="button" data-id="'.$order->id.'" class="btn btn-warning btn-sm btn-assign-close">Selling Close</a>';

                return $btn;
            })
            ->editColumn('status', function(SrProductAssignOrder $order) {

                if ($order->status == 0)
                    return '<label class="label label-warning">Selling Continue</label>';
                else
                    return '<label class="label label-success">Selling Closed</label>';
            })

            ->editColumn('date', function(SrProductAssignOrder $order) {
                return $order->date->format('j F, Y');
            })
            ->addColumn('assign_quantity', function(SrProductAssignOrder $order) {
                return number_format($order->srAssignProductItem->sum('assign_quantity'), 2);
            })
            ->editColumn('total_sale_amount', function(SrProductAssignOrder $order) {
                return number_format($order->total_sale_amount, 2);
            })
            ->editColumn('total_paid_amount', function(SrProductAssignOrder $order) {
                return number_format($order->total_paid_amount, 2);
            })
            ->addColumn('total_due_amount', function(SrProductAssignOrder $order) {
                return number_format($order->total_sale_amount - $order->total_paid_amount, 2);
            })
            ->addColumn('sale_quantity', function(SrProductAssignOrder $order) {
                return number_format($order->srAssignProductItem->sum('sale_quantity'), 2);
            })
            ->addColumn('back_quantity', function(SrProductAssignOrder $order) {
                return number_format($order->srAssignProductItem->sum('back_quantity'), 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }
}
