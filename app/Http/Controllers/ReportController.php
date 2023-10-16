<?php

namespace App\Http\Controllers;

use App\Models\AccountHeadSubType;
use App\Models\AccountHeadType;
use App\Models\BankAccount;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\MobileBanking;
use App\Models\ProductModel;
use App\Models\PurchaseInventory;
use App\Models\PurchaseInventoryLog;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProduct;
use App\Models\SalesOrder;
use App\Models\SalesRepresentative;
use App\Models\Supplier;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    public function purchase(Request $request) {
        $suppliers = Supplier::orderBy('name')->get();
        $appends = [];
        $query = PurchaseOrder::query();

        if ($request->date && $request->date != '') {
            $dates = explode(' - ', $request->date);
            if (count($dates) == 2) {
                $query->whereBetween('date', [$dates[0], $dates[1]]);
                $appends['date'] = $request->date;
            }
        }

        if ($request->supplier && $request->supplier != '') {
            $query->where('supplier_id', $request->supplier);
            $appends['supplier'] = $request->supplier;
        }

        if ($request->purchaseId && $request->purchaseId != '') {
            $query->where('order_no', $request->purchaseId);
            $appends['purchaseId'] = $request->purchaseId;
        }


        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');

        $data = [
            'total' => $query->sum('total'),
            'due' => $query->sum('due'),
            'paid' => $query->sum('paid'),
        ];

        $orders = $query->paginate(10);



        return view('report.purchase', compact('orders', 'suppliers', 'appends'))->with($data);
    }

    public function sale(Request $request) {

        $banks = Bank::where('status',1)->get();
        $customers = Customer::orderBy('name')->get();
        $products = ProductModel::orderBy('name')->get();
        $appends = [];
        $query = SalesOrder::whereIn('sale_type', [1,3]);

            if (auth()->user()->role == 2)
                $query->where('created_by', auth()->user()->id);

            if (auth()->user()->role == 3)
                $query->where('customer_id', auth()->user()->retailer_id);

            if ($request->date && $request->date != '') {
                $dates = explode(' - ', $request->date);
                if (count($dates) == 2) {
                    $query->whereBetween('date', [$dates[0], $dates[1]]);
                    $appends['date'] = $request->date;
                }
            }

            if ($request->customer && $request->customer != '') {
                $query->where('customer_id', $request->customer);
                $appends['customer'] = $request->customer;
            }
            if ($request->due && $request->due != '') {
                $query->where('due', '>', 0);
                $appends['due'] = $request->due;
            }

            if ($request->saleId && $request->saleId != '') {
                $query->where('order_no', $request->saleId);
                $appends['saleId'] = $request->saleId;
            }

            if ($request->product && $request->product != '') {
                $query->whereHas('products', function ($q) use ($request) {
                    $q->where('product_model_id', '=', $request->product);
                });

                $appends['product'] = $request->product;
            }


            $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc');

            $data = [
                'total' => $query->sum('total'),
                'due' => $query->sum('due'),
                'paid' => $query->sum('paid'),
                'refund' => $query->sum('refund'),
            ];

            $orders = $query->paginate(10);


            return view('report.sale', compact('customers', 'products',
                'appends', 'orders', 'banks'))->with($data);
        }

    public function SrSale(Request $request) {

        $banks = Bank::where('status',1)->get();
        $customers = Customer::orderBy('name')->get();
        $srs = SalesRepresentative::orderBy('name')->get();
        $appends = [];
        $query = SalesOrder::whereIn('sale_type', [2,3]);

            if (auth()->user()->role == 2)
                $query->where('created_by', auth()->user()->id);

            if (auth()->user()->role == 3)
                $query->where('customer_id', auth()->user()->retailer_id);

            if ($request->date && $request->date != '') {
                $dates = explode(' - ', $request->date);
                if (count($dates) == 2) {
                    $query->whereBetween('date', [$dates[0], $dates[1]]);
                    $appends['date'] = $request->date;
                }
            }

            if ($request->customer && $request->customer != '') {
                $query->where('customer_id', $request->customer);
                $appends['customer'] = $request->customer;
            }
            if ($request->sr && $request->sr != '') {
                $query->where('sr_id', $request->sr);
                $appends['sr'] = $request->sr;
            }
            if ($request->due && $request->due != '') {
                $query->where('due', '>', 0);
                $appends['due'] = $request->due;
            }

            if ($request->saleId && $request->saleId != '') {
                $query->where('order_no', $request->saleId);
                $appends['saleId'] = $request->saleId;
            }

            $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc');

            $data = [
                'total' => $query->sum('total'),
                'due' => $query->sum('due'),
                'paid' => $query->sum('paid'),
                'refund' => $query->sum('refund'),
            ];

            $orders = $query->paginate(10);


            return view('report.sr_sale', compact('customers',
                'appends', 'orders', 'banks','srs'))->with($data);
        }


    public function balanceSheet() {
        $bankAccounts = BankAccount::where('status', 1)->with('bank', 'branch')->get();
        $cash = Cash::first();
        $mobileBanking = MobileBanking::first();
        $customerTotalPaid = Customer::all()->sum('due');
        $suppliers = Supplier::all();
        $totalInventory = PurchaseInventory::where('shop_id',1)->where('quantity','>',0)->sum('selling_price');

        return view('report.balance_sheet', compact('bankAccounts',
            'cash', 'mobileBanking', 'customerTotalPaid', 'suppliers', 'totalInventory'));
    }

    public function profitAndLoss(Request $request) {
        $incomes = null;
        $expenses = null;

        if ($request->start && $request->end) {
            $incomes = TransactionLog::where('transaction_type', 1)->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [4,2,5])->whereBetween('date', [$request->start, $request->end])->get();
        }

        return view('report.profit_and_loss', compact('incomes', 'expenses'));
    }

    public function ledger(Request $request) {
        $incomes = null;
        $expenses = null;


//        if ($request->start && $request->end) {
//            $incomes = TransactionLog::whereIn('transaction_type', [1, 5])->whereBetween('date', [$request->start, $request->end])->get();
//            $expenses = TransactionLog::whereIn('transaction_type', [3, 2, 6])->whereBetween('date', [$request->start, $request->end])->get();
//        }
        if($request->start !='' && $request->end !='') {
            $incomes = TransactionLog::where('transaction_type', 1)
                ->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])
                ->whereBetween('date', [$request->start, $request->end])->get();

        }elseif ($request->start !='' && $request->end !='' && $request->accounthead !='' && $request->accountsubhead ==''  ) {

            $incomes = TransactionLog::where('transaction_type', 1)
                ->where('account_head_type_id', $request->accounthead)
                ->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])
                ->where('account_head_type_id', $request->accounthead)
                ->whereBetween('date', [$request->start, $request->end])->get();
        }elseif ($request->start !='' && $request->end !=''  && $request->accountsubhead !='' && $request->accounthead ==''  ) {

            $incomes = TransactionLog::where('transaction_type', 1)
                ->where('account_head_sub_type_id', $request->accountsubhead)
                ->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])
                ->where('account_head_sub_type_id', $request->accountsubhead)
                ->whereBetween('date', [$request->start, $request->end])->get();
        }
        elseif ($request->start !='' && $request->end !='' && $request->accounthead !='' && $request->accountsubhead !=''  ) {

            $incomes = TransactionLog::where('transaction_type', 1)
                ->where('account_head_type_id', $request->accounthead)
                ->where('account_head_sub_type_id', $request->accountsubhead)
                ->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])
                ->where('account_head_type_id', $request->accounthead)
                ->where('account_head_sub_type_id', $request->accountsubhead)
                ->whereBetween('date', [$request->start, $request->end])->get();
        }

        return view('report.ledger', compact('incomes', 'expenses'));
    }

    public function transaction(Request $request) {
        $result = null;
        $types = AccountHeadType::whereNotIn('id', [1, 2, 3, 4,5])->get();
        $subTypes = AccountHeadSubType::whereNotIn('id', [1, 2, 3, 4,5])->get();

        if ($request->start && $request->end) {
            $query = TransactionLog::query();
            $query->select(DB::raw('sum(amount) as amount, account_head_type_id, account_head_sub_type_id'));
            $query->whereBetween('date', [$request->start, $request->end]);
            $query->whereNotIn('account_head_type_id', [0, 1, 2, 3, 4, 20, 21]);
            $query->whereNotIn('account_head_sub_type_id', [0, 1, 2, 3, 4, 42, 43]);

            if ($request->type && $request->type != '')
                $query->where('account_head_type_id', $request->type);

            if ($request->sub_type && $request->sub_type != '')
                $query->where('account_head_sub_type_id', $request->sub_type);

            $query->groupBy('account_head_sub_type_id', 'account_head_type_id');
            $query->with('accountHead');

            $result = $query->get();
        }

        return view('report.transaction', compact('result', 'types',
            'subTypes'));
    }
    public function receiveAndPayment(Request $request){
        $incomes = null;
        $expenses = null;
        $incomeQuery = TransactionLog::query();
        $expenseQuery = TransactionLog::query();

        $incomeQuery->where('transaction_type', 1);
        $expenseQuery->where('transaction_type', 2);
        $incomeQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $expenseQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $incomeQuery->where('account_head_type_id','!=', 0);
        $expenseQuery->where('account_head_type_id','!=', 0);

        if ($request->account_head_type != '') {
            $incomeQuery->where('account_head_type_id', $request->account_head_type);
            $expenseQuery->where('account_head_type_id', $request->account_head_type);
        }

        if ($request->start != '') {
            $incomeQuery->where('date', '>=', $request->start);
            $expenseQuery->where('date', '>=', $request->start);
        }

        if ($request->end != '') {
            $incomeQuery->where('date', '<=', $request->end);
            $expenseQuery->where('date', '<=', $request->end);
        }

        $incomeQuery->groupBy('account_head_type_id');
        $expenseQuery->groupBy('account_head_type_id');

        $incomes = $incomeQuery->get();
        $expenses = $expenseQuery->get();
        return view('report.receive_and_payment',compact('incomes','expenses'));
    }
    public function stockReport(Request $request){

        return view('report.stock_report');
    }
    public function bankStatement (Request $request){
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $result = null;
        $metaData = null;

        if ($request->bank && $request->branch && $request->account && $request->start && $request->end) {
            $bankAccount = BankAccount::where('id', $request->account)->first();
            $bank = Bank::where('id', $request->bank)->first();
            $branch = Branch::where('id', $request->branch)->first();

            $metaData = [
                'name' => $bank->name,
                'branch' => $branch->name,
                'account' => $bankAccount->account_no,
                'start_date' => $request->start,
                'end_date' => $request->end,
            ];

            $result = collect();

            $initialBalance = $bankAccount->opening_balance;
            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            $totalIncome = TransactionLog::where('transaction_type', 1)
                ->where('bank_account_id', $request->account)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $totalExpense = TransactionLog::where('transaction_type', 2)
                ->where('bank_account_id', $request->account)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $openingBalance = $initialBalance + $totalIncome - $totalExpense;

            $result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            $transactionLogs = TransactionLog::where('bank_account_id', $request->account)
                ->whereBetween('date', [$request->start, $request->end])
                ->get();

            $balance = $openingBalance;
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($transactionLogs as $log) {
                if ($log->transaction_type == 1) {
                    // Income
                    $balance += $log->amount;
                    $totalDebit += $log->amount;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => $log->amount, 'credit' => '', 'balance' => $balance]);
                } else {
                    $balance -= $log->amount;
                    $totalCredit += $log->amount;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => '', 'credit' => $log->amount, 'balance' => $balance]);
                }
            }

            $metaData['total_debit'] = $totalDebit;
            $metaData['total_credit'] = $totalCredit;

        }
        return view('report.bank_statement', compact('banks', 'result', 'metaData'));
    }

    public function retailerReport(Request $request) {

        $banks = Bank::where('status',1)->get();
        $customers = Customer::orderBy('name')->get();
        $products = ProductModel::orderBy('name')->get();
        $appends = [];
        $query = PurchaseInventoryLog::where('sales_order_id','!=',null);
        if (auth()->user()->role == 2)
            $query->where('created_by',auth()->user()->id);

        if (auth()->user()->role == 3)
            $query->where('customer_id',auth()->user()->retailer_id);

        if ($request->date && $request->date != '') {
            $dates = explode(' - ', $request->date);
            if (count($dates) == 2) {
                $query->whereBetween('date', [$dates[0], $dates[1]]);
                $appends['date'] = $request->date;
            }
        }

        if ($request->customer && $request->customer != '') {
            $query->where('customer_id', $request->customer);
            $appends['customer'] = $request->customer;
        }

        if ($request->product && $request->product != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('product_model_id', '=', $request->product);
            });

            $appends['product'] = $request->product;
        }

        $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

//        $data = [
//            'total' => $query->sum('total'),
//            'due' => $query->sum('due'),
//            'paid' => $query->sum('paid'),
//            'refund' => $query->sum('refund'),
//        ];

        $orders = $query->paginate(10);

//        return view('report.retailer_report', compact('customers', 'products',
//            'appends', 'orders','banks'))->with($data);
  return view('report.retailer_report', compact('customers', 'products',
            'appends', 'orders','banks'));
    }
}
