<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth','check.status'])->group(function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('payment-details', 'DashboardController@paymentDetails')->name('payment.details');

    // Warehouse
    Route::get('warehouse', 'WarehouseController@index')->name('warehouse')->middleware('permission:warehouse');
    Route::get('warehouse/add', 'WarehouseController@add')->name('warehouse.add')->middleware('permission:warehouse');
    Route::post('warehouse/add', 'WarehouseController@addPost')->middleware('permission:warehouse');
    Route::get('warehouse/edit/{warehouse}', 'WarehouseController@edit')->name('warehouse.edit')->middleware('permission:warehouse');
    Route::post('warehouse/edit/{warehouse}', 'WarehouseController@editPost')->middleware('permission:warehouse');

    // Bank
    Route::get('bank', 'BankController@index')->name('bank')->middleware('permission:bank');
    Route::get('bank/add', 'BankController@add')->name('bank.add')->middleware('permission:bank');
    Route::post('bank/add', 'BankController@addPost')->middleware('permission:bank');
    Route::get('bank/edit/{bank}', 'BankController@edit')->name('bank.edit')->middleware('permission:bank');
    Route::post('bank/edit/{bank}', 'BankController@editPost')->middleware('permission:bank');

    // Bank Branch
    Route::get('bank-branch', 'BranchController@index')->name('branch')->middleware('permission:branch');
    Route::get('bank-branch/add', 'BranchController@add')->name('branch.add')->middleware('permission:branch');
    Route::post('bank-branch/add', 'BranchController@addPost')->middleware('permission:branch');
    Route::get('bank-branch/edit/{branch}', 'BranchController@edit')->name('branch.edit')->middleware('permission:branch');
    Route::post('bank-branch/edit/{branch}', 'BranchController@editPost')->middleware('permission:branch');

    // Bank Account
    Route::get('bank-account', 'BankAccountController@index')->name('bank_account')->middleware('permission:account');
    Route::get('bank-account/add', 'BankAccountController@add')->name('bank_account.add')->middleware('permission:account');
    Route::post('bank-account/add', 'BankAccountController@addPost')->middleware('permission:account');
    Route::get('bank-account/edit/{account}', 'BankAccountController@edit')->name('bank_account.edit')->middleware('permission:account');
    Route::post('bank-account/edit/{account}', 'BankAccountController@editPost')->middleware('permission:account');
    Route::get('bank-account/get-branches', 'BankAccountController@getBranches')->name('bank_account.get_branch')->middleware('permission:account');

    // Supplier
    Route::get('supplier', 'SupplierController@index')->name('supplier')->middleware('permission:supplier');
    Route::get('supplier/add', 'SupplierController@add')->name('supplier.add')->middleware('permission:supplier');
    Route::post('supplier/add', 'SupplierController@addPost')->middleware('permission:supplier');
    Route::get('supplier/edit/{supplier}', 'SupplierController@edit')->name('supplier.edit')->middleware('permission:supplier');
    Route::post('supplier/edit/{supplier}', 'SupplierController@editPost')->middleware('permission:supplier');

    // Shop
    Route::get('shop', 'ShopController@index')->name('shop');
    Route::get('shop/add', 'ShopController@add')->name('shop.add');
    Route::post('shop/add', 'ShopController@addPost');
    Route::get('shop/edit/{shop}', 'ShopController@edit')->name('shop.edit');
    Route::post('shop/edit/{shop}', 'ShopController@editPost');

    //Product Color
    Route::get('product-color', 'ProductColorController@index')->name('product_color');
    Route::get('product-color/add', 'ProductColorController@add')->name('product_color.add');
    Route::post('product-color/add', 'ProductColorController@addPost');
    Route::get('product-color/edit/{productColor}', 'ProductColorController@edit')->name('product_color.edit');
    Route::post('product-color/edit/{productColor}', 'ProductColorController@editPost');

    //Product Type
    Route::get('product-type', 'ProductTypeController@index')->name('product_type');
    Route::get('product-type/add', 'ProductTypeController@add')->name('product_type.add');
    Route::post('product-type/add', 'ProductTypeController@addPost');
    Route::get('product-type/edit/{productType}', 'ProductTypeController@edit')->name('product_type.edit');
    Route::post('product-type/edit/{productType}', 'ProductTypeController@editPost');

    // Purchase Product
    Route::get('product-model-no', 'ProductModelController@index')->name('purchase_product')->middleware('permission:purchase_product');
    Route::get('product-model-no/add', 'ProductModelController@add')->name('purchase_product.add')->middleware('permission:purchase_product');
    Route::post('product-model-no/add', 'ProductModelController@addPost')->middleware('permission:purchase_product');
    Route::get('product-model-no/edit/{productModel}', 'ProductModelController@edit')->name('purchase_product.edit')->middleware('permission:purchase_product');
    Route::post('product-model-no/edit/{productModel}', 'ProductModelController@editPost')->middleware('permission:purchase_product');
    Route::post('product-model-no/update', 'ProductModelController@modelPriceUpdate')->name('model_price.update')->middleware('permission:purchase_product');
    Route::get('product-price-list/update', 'ProductModelController@PriceUpdateList')->name('update_price')->middleware('permission:purchase_product');

    //  Brand
    Route::get('brand', 'ProductBrandController@index')->name('purchase_product_category')->middleware('permission:purchase_product_category');
    Route::get('brand/add', 'ProductBrandController@add')->name('purchase_product_category.add')->middleware('permission:purchase_product_category');
    Route::post('brand/add', 'ProductBrandController@addPost')->middleware('permission:purchase_product');
    Route::get('brand/edit/{productBrand}', 'ProductBrandController@edit')->name('purchase_product_category.edit')->middleware('permission:purchase_product_category');
    Route::post('brand/edit/{productBrand}', 'ProductBrandController@editPost')->middleware('permission:purchase_product_category');

    // Purchase Order
    Route::get('purchase-order', 'PurchaseController@purchaseOrder')->name('purchase_order.create');
    Route::get('purchase-order1', 'PurchaseController@purchaseOrder1')->name('purchase_order.create1');
    Route::post('purchase-order', 'PurchaseController@purchaseOrderPost')->middleware('permission:purchase_order');
    Route::get('purchase-order/edit/{id}', 'PurchaseController@purchaseOrderEdit')->name('purchase_order.edit')->middleware('permission:edit_purchase_order');
    Route::post('purchase-order/edit/{id}', 'PurchaseController@purchaseOrderEditPost')->middleware('permission:edit_purchase_order');
    Route::get('purchase-product-json', 'PurchaseController@purchaseProductJson')->name('purchase_product.json');

    // Purchase Receipt
    Route::get('purchase-receipt', 'PurchaseController@purchaseReceipt')->name('purchase_receipt.all')->middleware('permission:purchase_receipt');
    Route::get('purchase-receipt/datatable', 'PurchaseController@purchaseReceiptDatatable')->name('purchase_receipt.datatable');

    Route::get('purchase-receipt/details/{order}', 'PurchaseController@purchaseReceiptDetails')->name('purchase_receipt.details');
    Route::get('purchase-receipt/print/{order}', 'PurchaseController@purchaseReceiptPrint')->name('purchase_receipt.print');

    Route::get('purchase-receipt/payment/details/{payment}', 'PurchaseController@purchasePaymentDetails')->name('purchase_receipt.payment_details');
    Route::get('purchase-receipt/payment/print/{payment}', 'PurchaseController@purchasePaymentPrint')->name('purchase_receipt.payment_print');
    Route::get('purchase-receipt/qr-code/{order}', 'PurchaseController@qrCode')->name('purchase_receipt.qr_code');
    Route::get('purchase-receipt/qr-code/print/{order}', 'PurchaseController@qrCodePrint')->name('purchase_receipt.qr_code_print');
    Route::get('purchase-single-receipt/qr-code/print/{order}', 'PurchaseController@qrSingleCodePrint')->name('purchase_receipt.qr_code_single_print');
    Route::get('purchase-receipt/edit/{order}', 'PurchaseController@purchaseReceiptEdit')->name('purchase_receipt.edit');
    Route::post('purchase-receipt/edit/{order}', 'PurchaseController@purchaseReceiptEditPost');
    Route::get('purchase-receipt/check-delete-status', 'PurchaseController@checkDeleteStatus')->name('purchase_receipt.check_delete_status');

    //Purchase Stock Transfer
    Route::get('stock-transfer/invoice', 'PurchaseController@stockTransferInvoice')->name('stock_transfer.invoice');
    Route::get('purchase-stock-transfer','PurchaseController@purchaseStockTransfer')->name('purchase_stock_transfer');
    Route::post('purchase-stock-transfer','PurchaseController@purchaseStockTransferPost');
    Route::get('transfer-order/shop-transfer-inventory/product/details', 'PurchaseController@transferProductDetails')->name('transfer_product.details');

    Route::get('transfer-receipt/datatable', 'PurchaseController@transferReceiptDatatable')->name('transfer_receipt.datatable');

    Route::get('transfer-receipt/details/{order}', 'PurchaseController@transferReceiptDetails')->name('transfer_receipt.details');
    Route::get('transfer-receipt/print/{order}', 'PurchaseController@transferReceiptPrint')->name('transfer_receipt.print');

    // Supplier Payment
    Route::get('supplier-payment', 'PurchaseController@supplierPayment')->name('supplier_payment.all')->middleware('permission:purchase_inventory');
    Route::get('supplier-payment/get-orders', 'PurchaseController@supplierPaymentGetOrders')->name('supplier_payment.get_orders');
    Route::get('supplier-payment/get-refund-orders', 'PurchaseController@supplierPaymentGetRefundOrders')->name('supplier_payment.get_refund_orders');
    Route::get('supplier-payment/order-details', 'PurchaseController@supplierPaymentOrderDetails')->name('supplier_payment.order_details');
    Route::post('supplier-payment/payment', 'PurchaseController@makePayment')->name('supplier_payment.make_payment');
    Route::post('supplier-payment/refund', 'PurchaseController@makeRefund')->name('supplier_payment.make_refund');

    // Purchase Inventory
    Route::get('purchase-inventory', 'PurchaseController@purchaseInventory')->name('purchase_inventory.all')->middleware('permission:purchase_inventory');
    Route::get('purchase-inventory-summary', 'PurchaseController@purchaseInventorySummary')->name('purchase_inventory_summary.all')->middleware('permission:purchase_inventory');
    Route::get('purchase-inventory/datatable', 'PurchaseController@purchaseInventoryDatatable')->name('purchase_inventory.datatable');
    Route::get('purchase-inventory-summary/datatable', 'PurchaseController@purchaseInventorySummaryDatatable')->name('purchase_inventory_summary.datatable');
    Route::get('purchase-inventory/details/datatable', 'PurchaseController@purchaseInventoryDetailsDatatable')->name('purchase_inventory.details.datatable');
    Route::get('purchase-inventory/details/{product}/{warehouse}', 'PurchaseController@purchaseInventoryDetails')->name('purchase_inventory.details');
    Route::get('purchase-inventory/qr-code/{product}/{warehouse}', 'PurchaseController@purchaseInventoryQrCode')->name('purchase_inventory.qr_code');
    Route::get('shop-purchase-inventory', 'PurchaseController@shopPurchaseInventory')->name('shop_purchase_inventory.all')->middleware('permission:purchase_inventory');
    Route::get('shop-purchase-inventory/datatable', 'PurchaseController@shopPurchaseInventoryDatatable')->name('shop_purchase_inventory.datatable');

    // Customer
    Route::get('retailer', 'CustomerController@index')->name('customer');
    Route::get('retailer/add', 'CustomerController@add')->name('customer.add');
    Route::post('retailer/add', 'CustomerController@addPost');
    Route::get('retailer/edit/{customer}', 'CustomerController@edit')->name('customer.edit');
    Route::post('retailer/edit/{customer}', 'CustomerController@editPost');
    Route::get('retailer/datatable', 'CustomerController@datatable')->name('customer.datatable');

   // Customer Service
    Route::get('customer-service', 'CoustomerService@index')->name('customer_service');
    Route::get('customer-service/add', 'CoustomerService@add')->name('customer_service.add');
    Route::post('customer-service/add', 'CoustomerService@addPost');
    Route::get('customer-service/edit/{customerService}', 'CoustomerService@edit')->name('customer_service.edit');
    Route::post('customer-service/edit/{customerService}', 'CoustomerService@editPost');
    Route::post('customer-service/pending', 'CoustomerService@pending')->name('product_pending');
    Route::post('customer-service/receive-from-company', 'CoustomerService@receiveFromCompany')->name('receive_from_company');
    Route::post('customer-service/delivery-complete', 'CoustomerService@deliveryComplete')->name('delivery_complete');
    Route::post('customer-service/delete', 'CoustomerService@delete')->name('delete_customer');

    // SR
    Route::get('sales-representative', 'SalesRepresentativeController@index')->name('sales_representative');
    Route::get('sales-representative/add', 'SalesRepresentativeController@add')->name('sales_representative.add');
    Route::post('sales-representative/add', 'SalesRepresentativeController@addPost');
    Route::get('sales-representative/edit/{sale_representative}', 'SalesRepresentativeController@edit')->name('sales_representative.edit');
    Route::post('sales-representative/edit/{sale_representative}', 'SalesRepresentativeController@editPost');
    Route::get('sales-representative/datatable', 'SalesRepresentativeController@datatable')->name('sales_representative.datatable');
    //Sr Product Assign

    Route::get('product-assign-list', 'SalesRepresentativeController@srProductAssignList')->name('sr_product_assign_list');
    Route::get('sr-product-assign', 'SalesRepresentativeController@srProductAssignReceipt')->name('sr_product_assign.all');
    Route::get('sr-product-assign/datatable', 'SalesRepresentativeController@srProductAssignReceiptDatatable')->name('sr_product_assign.datatable');
    Route::get('sr-product-assign/details/{order}', 'SalesRepresentativeController@srProductAssignReceiptDetails')->name('sr_product_assign.details');
    Route::post('sr_assign_product_order_close', 'SalesRepresentativeController@srProductAssignOrderClose')->name('sr_assign_product_order.close');
    Route::get('sr-product-assign/create', 'SalesRepresentativeController@productAssignCreate')->name('sr_product_assign.create');
    Route::post('sr-product-assign/create', 'SalesRepresentativeController@productAssignCreatePost');


    // Sales Order
    Route::get('sales-order', 'SaleController@salesOrder')->name('sales_order.create');
    Route::post('sales-order', 'SaleController@salesOrderPost');
    Route::get('sale-order/shop-purchase-inventory/product/details', 'SaleController@saleProductDetails')->name('sale_product.details');
    Route::post('sr-sale-approve', 'SaleController@srSaleApprovePost')->name('sr_sale_approve');

    // SR Sales Order
    Route::get('sr-sales-order', 'SaleController@srSalesOrder')->name('sr_sales_order.create');
    Route::post('sr-sales-order', 'SaleController@srSalesOrderPost');
//    Route::get('sr-sale-order/shop-purchase-inventory/product/details', 'SaleController@saleProductDetails')->name('sale_product.details');

    // wastage
    Route::get('wastage', 'WastageController@wastage')->name('wastage.create')->middleware('permission:wastage_order');
    Route::post('wastage', 'WastageController@wastagePost')->middleware('permission:wastage_order');
    Route::get('wastage/index', 'WastageController@index')->name('wastage.index')->middleware('permission:wastage_report');
    Route::get('wastage/datatable', 'WastageController@wastageDatatable')->name('wastage.datatable');
    Route::get('wastage/details/{wastage_id}', 'WastageController@wastageProductDetails')->name('wastage.details')->middleware('permission:wastage_report');
    Route::get('wastage/print/{wastage_id}', 'WastageController@wastageProductPrint')->name('wastage.print')->middleware('permission:wastage_report');

    // Sale Receipt
    Route::get('sale-receipt', 'SaleController@saleReceipt')->name('sale_receipt.all')->middleware('permission:sale_receipt');
    Route::get('sale-receipt/details/{order}', 'SaleController@saleReceiptDetails')->name('sale_receipt.details');
    Route::get('sale-receipt/print/{order}', 'SaleController@saleReceiptPrint')->name('sale_receipt.print');
    Route::get('sale-receipt/pdf/{order}', 'SaleController@saleReceiptPdf')->name('sale_receipt.pdf');
    Route::get('sale-receipt/datatable', 'SaleController@saleReceiptDatatable')->name('sale_receipt.datatable');
    Route::post('sale-receipt/payment', 'SaleController@makePayment')->name('sale_receipt.make_payment');
    Route::get('sale-receipt/payment/details/{payment}', 'SaleController@salePaymentDetails')->name('sale_receipt.payment_details');
    Route::get('sale-receipt/payment/print/{payment}', 'SaleController@salePaymentPrint')->name('sale_receipt.payment_print');
    Route::get('sale-receipt/edit/{order}', 'SaleController@saleReceiptEdit')->name('sale_receipt.edit');
    Route::post('sale-receipt/edit/{order}', 'SaleController@saleReceiptEditPost');

    // Customer Payment
    Route::get('customer-payment', 'SaleController@customerPayment')->name('customer_payment.all')->middleware('permission:customer_payment');
    Route::get('customer-payment/datatable', 'SaleController@customerPaymentDatatable')->name('customer_payment.datatable');
    Route::get('customer-payment/get-orders', 'SaleController@customerPaymentGetOrders')->name('customer_payment.get_orders');
    Route::get('customer-payment/get-refund-orders', 'SaleController@customerPaymentGetRefundOrders')->name('customer_payment.get_refund_orders');
    Route::post('customer-payment/payment', 'SaleController@customerMakePayment')->name('customer_payment.make_payment');
    Route::post('customer-payment/refund', 'SaleController@customerMakeRefund')->name('customer_payment.make_refund');

    // Product Sale Information
    Route::get('sale-information', 'SaleController@saleInformation')->name('sale_information.index')->middleware('permission:product_sale_information');
    Route::post('sale-information/post', 'SaleController@saleInformationPost')->name('sale_information.post')->middleware('permission:product_sale_information');
    Route::get('sale-information/print/{purchaseOrder}/{saleOrder}', 'SaleController@saleInformationPrint')->name('sale_information.print')->middleware('permission:product_sale_information');

    // Account Head Type
    Route::get('account-head/type', 'AccountsController@accountHeadType')->name('account_head.type')->middleware('permission:account_head_type');
    Route::get('account-head/type/add', 'AccountsController@accountHeadTypeAdd')->name('account_head.type.add')->middleware('permission:account_head_type');
    Route::post('account-head/type/add', 'AccountsController@accountHeadTypeAddPost')->middleware('permission:account_head_type');
    Route::get('account-head/type/edit/{type}', 'AccountsController@accountHeadTypeEdit')->name('account_head.type.edit')->middleware('permission:account_head_type');
    Route::post('account-head/type/edit/{type}', 'AccountsController@accountHeadTypeEditPost')->middleware('permission:account_head_type');

    // Account Head Sub Type
    Route::get('account-head/sub-type', 'AccountsController@accountHeadSubType')->name('account_head.sub_type')->middleware('permission:account_head_sub_type');
    Route::get('account-head/sub-type/add', 'AccountsController@accountHeadSubTypeAdd')->name('account_head.sub_type.add')->middleware('permission:account_head_sub_type');
    Route::post('account-head/sub-type/add', 'AccountsController@accountHeadSubTypeAddPost')->middleware('permission:account_head_sub_type');
    Route::get('account-head/sub-type/edit/{subType}', 'AccountsController@accountHeadSubTypeEdit')->name('account_head.sub_type.edit')->middleware('permission:account_head_sub_type');
    Route::post('account-head/sub-type/edit/{subType}', 'AccountsController@accountHeadSubTypeEditPost')->middleware('permission:account_head_sub_type');

    // Transaction
    Route::get('transaction', 'AccountsController@transactionIndex')->name('transaction.all')->middleware('permission:transaction');
    Route::get('transaction/datatable', 'AccountsController@transactionDatatable')->name('transaction.datatable');
    Route::get('transaction/add', 'AccountsController@transactionAdd')->name('transaction.add')->middleware('permission:transaction');
    Route::post('transaction/add', 'AccountsController@transactionAddPost')->middleware('permission:transaction');
    Route::post('transaction/edit', 'AccountsController@transactionEditPost')->name('transaction.edit_post')->middleware('permission:transaction');
    Route::get('transaction/details/json', 'AccountsController@transactionDetailsJson')->name('transaction.details_json');
    Route::get('transaction/details/{transaction}', 'AccountsController@transactionDetails')->name('transaction.details');
    Route::get('transaction/print/{transaction}', 'AccountsController@transactionPrint')->name('transaction.print');

    // Balance Transfer
    Route::get('balance-transfer/add', 'AccountsController@balanceTransferAdd')->name('balance_transfer.add')->middleware('permission:balance_transfer');
    Route::post('balance-transfer/add', 'AccountsController@balanceTransferAddPost')->middleware('permission:balance_transfer');

    // Report
    Route::get('report/purchase', 'ReportController@purchase')->name('report.purchase')->middleware('permission:purchase_report');
    Route::get('report/sale', 'ReportController@sale')->name('report.sale');
    Route::get('report/balance-sheet', 'ReportController@balanceSheet')->name('report.balance_sheet')->middleware('permission:balance_sheet');
    Route::get('report/profit-and-loss', 'ReportController@profitAndLoss')->name('report.profit_and_loss')->middleware('permission:profit_and_loss');
    Route::get('report/receive-and-payment', 'ReportController@receiveAndPayment')->name('report.receive_and_payment')->middleware('permission:receive_and_payment_report');
    Route::get('report/stock-report', 'ReportController@stockReport')->name('report.stock_report')->middleware('permission:purchase_report');
    Route::get('report/bank-statement', 'ReportController@bankStatement')->name('report.bank_statement')->middleware('permission:bank_statement_report');
    Route::get('report/ledger', 'ReportController@ledger')->name('report.ledger')->middleware('permission:ledger');
    Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction')->middleware('permission:transaction_report');
    Route::get('report/retailer', 'ReportController@retailerReport')->name('report.retailer');
    Route::get('report/sr-sale', 'ReportController@SrSale')->name('report.sr_sale');
    // User Management
    Route::get('user', 'UserController@index')->name('user.all')->middleware('permission:users');
    Route::get('user/add', 'UserController@add')->name('user.add')->middleware('permission:users');
    Route::post('user/add', 'UserController@addPost')->middleware('permission:users');
    Route::get('user/edit/{user}', 'UserController@edit')->name('user.edit')->middleware('permission:users');
    Route::post('user/edit/{user}', 'UserController@editPost')->middleware('permission:users');

    // Common
    Route::get('get-branch', 'CommonController@getBranch')->name('get_branch');
    Route::get('get-bank-account', 'CommonController@getBankAccount')->name('get_bank_account');
    Route::get('order-details', 'CommonController@orderDetails')->name('get_order_details');
    Route::get('get-account-head-type', 'CommonController@getAccountHeadType')->name('get_account_head_type');
    Route::get('get-account-head-sub-type', 'CommonController@getAccountHeadSubType')->name('get_account_head_sub_type');
    Route::get('get-serial-suggestion', 'CommonController@getSerialSuggestion')->name('get_serial_suggestion');
    //Route::get('vat-correction', 'CommonController@vatCorrection');
    Route::get('get-sub-category', 'CommonController@getSubCategory')->name('get_subCategory');
    Route::get('get_product_type', 'CommonController@getProductType')->name('get_product_type');
    Route::get('get-brands', 'CommonController@getBrands')->name('get_brands');
    Route::get('get-product', 'CommonController@getProduct')->name('get_product');
    Route::get('get-product-color', 'CommonController@getProductColor')->name('get_product_color');
    Route::get('get-product-models', 'CommonController@getProductModel')->name('get_product_models');
    Route::get('get-product-code', 'CommonController@getProductCode')->name('get_product_code');
    Route::get('get-product-unit-price', 'CommonController@getProductUnitPrice')->name('get_product_model_price');
    Route::get('cash', 'CommonController@cash')->name('cash');
    Route::post('cash', 'CommonController@cashPost');
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});


require __DIR__.'/auth.php';
