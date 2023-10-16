<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
{{--    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />--}}

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .select2-container--default.select2-container--focus, .select2-selection.select2-container--focus, .select2-container--default:focus, .select2-selection:focus, .select2-container--default:active, .select2-selection:active {
            width: 100% !important;
        }
    </style>
    @yield('style')

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/backend/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('themes/backend/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/backend/css/custom.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="logo">
            @if(auth()->user()->role == \App\Enumeration\Role::$ADMIN)
            <span class="logo-mini"><b>AP</b></span>
            <span class="logo-lg"><b>Admin</b>Panel</span>
            @elseif(auth()->user()->role == \App\Enumeration\Role::$SR)
                <span class="logo-mini"><b>SR-P</b></span>
                <span class="logo-lg"><b>SR</b>Panel</span>
            @else
                <span class="logo-mini"><b>RP</b></span>
                <span class="logo-lg"><b>Retailer</b>Panel</span>
            @endif
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <h4 class="pull-left" style="color: white; margin-top: 15px; padding-left: 20px">{{ config('app.name') }}</h4>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- About 2ait -->
                    <li class="">
                        <a href="https://2aitautomation.com/about2ait" target="_blank" style="color:#fff;">
                            About 2ait
                        </a>
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{{ count($layoutData['nextPayments']) }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have {{ count($layoutData['nextPayments']) }} notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    @foreach($layoutData['nextPayments'] as $nextPayment)
                                    <li>
                                        <a href="{{ route('sale_receipt.details', ['order' => $nextPayment->id]) }}">
                                            <i class="fa fa-dollar text-success"></i> Order No. {{ $nextPayment->order_no }} payment date
                                        </a>
                                    </li>
                                    @endforeach

{{--                                    @foreach($layoutData['stocks'] as $stock)--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('purchase_inventory.all') }}" title="{{ $stock->product->name }} stock {{ $stock->quantity }} pcs in {{ $stock->warehouse->name }}">--}}
{{--                                            <i class="fa fa-calculator text-warning"></i> {{ $stock->product->name }} stock {{ $stock->quantity }} pcs in {{ $stock->warehouse->name }}--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    @endforeach--}}
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('img/avatar.png') }}" class="user-image" alt="Avatar">
                            <span class="hidden-xs">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-circle-o text-blue"></i> <span>Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role == \App\Enumeration\Role::$ADMIN)

                <?php
                $subMenu = ['user.all', 'user.edit', 'user.add'];
                ?>

                @can('administrator')
                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-blue"></i> <span>Administrator</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                        @can('users')
                            <li class="{{ Route::currentRouteName() == 'user.all' ? 'active' : '' }}">
                                <a href="{{ route('user.all') }}"><i class="fa fa-circle-o"></i> Users</a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <?php
                $subMenu = ['bank', 'bank.add', 'bank.edit', 'branch', 'branch.add', 'branch.edit',
                    'bank_account', 'bank_account.add', 'bank_account.edit','balance_transfer.add',
                    'report.bank_statement','cash',];
                ?>

                @can('bank_and_account')
                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-blue"></i> <span>Bank & Account</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        @can('bank')
                        <li class="{{ Route::currentRouteName() == 'bank' ? 'active' : '' }}">
                            <a href="{{ route('bank') }}"><i class="fa fa-circle-o"></i> Bank</a>
                        </li>
                        @endcan

                        @can('branch')
                        <li class="{{ Route::currentRouteName() == 'branch' ? 'active' : '' }}">
                            <a href="{{ route('branch') }}"><i class="fa fa-circle-o"></i> Branch</a>
                        </li>
                        @endcan

                        @can('account')
                        <li class="{{ Route::currentRouteName() == 'bank_account' ? 'active' : '' }}">
                            <a href="{{ route('bank_account') }}"><i class="fa fa-circle-o"></i> Account</a>
                        </li>
                        @endcan
                        @can('balance_transfer')
                            <li class="{{ Route::currentRouteName() == 'balance_transfer.add' ? 'active' : '' }}">
                                <a href="{{ route('balance_transfer.add') }}"><i class="fa fa-circle-o"></i> Balance Transfer</a>
                            </li>
                        @endcan
                        @can('bank_statement_report')
                            <li class="{{ Route::currentRouteName() == 'report.bank_statement' ? 'active' : '' }}">
                                <a href="{{ route('report.bank_statement') }}"><i class="fa fa-circle-o"></i> Bank Statement</a>
                            </li>
                        @endcan

                            <li class="{{ Route::currentRouteName() == 'cash' ? 'active' : '' }}">
                                <a href="{{ route('cash') }}"><i class="fa fa-circle-o"></i> Cash </a>
                            </li>

                    </ul>
                </li>
                @endcan

                    <?php
                    $subMenu = ['product_type','product_type.add','product_type.edit','purchase_product', 'purchase_product.add',
                        'purchase_product.edit','purchase_product_category', 'purchase_product_category.add',
                        'purchase_product_category.edit','update_price','product_color','product_color.add','product_color.edit'];
                    ?>

                        <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-circle-o text-blue"></i> <span>Product Information</span>
                                <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                            </a>
                            <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                                @can('purchase_product')
                                <li class="{{ Route::currentRouteName() == 'product_color' ? 'active' : '' }}">
                                    <a href="{{ route('product_color') }}"><i class="fa fa-circle-o"></i>Color</a>
                                </li>
                                @endcan   @can('purchase_product')
                                <li class="{{ Route::currentRouteName() == 'product_type' ? 'active' : '' }}">
                                    <a href="{{ route('product_type') }}"><i class="fa fa-circle-o"></i> Product Type</a>
                                </li>
                                @endcan
                                @can('purchase_product')
                                    <li class="{{ Route::currentRouteName() == 'purchase_product_category' ? 'active' : '' }}">
                                        <a href="{{ route('purchase_product_category') }}"><i class="fa fa-circle-o"></i> Brand</a>
                                    </li>
                                @endcan
                                @can('purchase_product')
                                    <li class="{{ Route::currentRouteName() == 'purchase_product' ? 'active' : '' }}">
                                        <a href="{{ route('purchase_product') }}"><i class="fa fa-circle-o"></i> Brand Model</a>
                                    </li>
                                @endcan
                                @can('purchase_product')
                                    <li class="{{ Route::currentRouteName() == 'update_price' ? 'active' : '' }}">
                                        <a href="{{ route('update_price') }}"><i class="fa fa-circle-o"></i>Update Price</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>


                <?php
                $subMenu = ['supplier', 'supplier.add', 'supplier.edit','purchase_product_sub_category', 'purchase_product_sub_category.add',
                    'purchase_product_sub_category.edit',
                    'purchase_order.create', 'purchase_receipt.qr_code', 'supplier_payment.all',
                    'purchase_receipt.payment_details','purchase_inventory_summary.all',
                    'purchase_inventory.qr_code','purchase_receipt.details',
                    'purchase_receipt.edit','report.purchase','purchase_order.edit','purchase_receipt.all',
                ];
                ?>

                @can('purchase')
                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-blue"></i> <span>Purchase</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                        @can('supplier')
                        <li class="{{ Route::currentRouteName() == 'supplier' ? 'active' : '' }}">
                            <a href="{{ route('supplier') }}"><i class="fa fa-circle-o"></i> Supplier</a>
                        </li>
                        @endcan

                        @can('purchase_order')
                        <li class="{{ Route::currentRouteName() == 'purchase_order.create' ? 'active' : '' }}">
                            <a href="{{ route('purchase_order.create') }}"><i class="fa fa-circle-o"></i> Purchase Order</a>
                        </li>
                        @endcan

                        @can('purchase_receipt')
                        <li class="{{ Route::currentRouteName() == 'purchase_receipt.all' ? 'active' : '' }}">
                            <a href="{{ route('purchase_receipt.all') }}"><i class="fa fa-circle-o"></i> Purchase Receipt</a>
                        </li>
                        @endcan

                        @can('supplier_payment')
                        <li class="{{ Route::currentRouteName() == 'supplier_payment.all' ? 'active' : '' }}">
                            <a href="{{ route('supplier_payment.all') }}"><i class="fa fa-circle-o"></i> Supplier Payment</a>
                        </li>
                        @endcan
                        @can('purchase_report')
                            <li class="{{ Route::currentRouteName() == 'report.purchase' ? 'active' : '' }}">
                                <a href="{{ route('report.purchase') }}"><i class="fa fa-circle-o"></i> Purchase Report</a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                    <?php
                    $subMenu = ['warehouse', 'warehouse.add', 'warehouse.edit','purchase_inventory.details',
                        'purchase_order.edit','stock_transfer.invoice','purchase_stock_transfer','shop_purchase_inventory.all',
                        'shop','purchase_inventory.all','shop.add','shop.edit','transfer_receipt.details'
                    ];
                    ?>

                    @can('purchase')
                        <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-circle-o text-blue"></i> <span>Stock</span>
                                <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                            </a>
                            <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                                @can('warehouse')
                                    <li class="{{ Route::currentRouteName() == 'warehouse' ? 'active' : '' }}">
                                        <a href="{{ route('warehouse') }}"><i class="fa fa-circle-o"></i> Warehouse</a>
                                    </li>
                                @endcan
                                <li class="{{ Route::currentRouteName() == 'shop' ? 'active' : '' }}">
                                    <a href="{{ route('shop') }}"><i class="fa fa-circle-o"></i>Shop</a>
                                </li>
                                @can('purchase_inventory')
                                    <li class="{{ Route::currentRouteName() == 'purchase_inventory.all' ? 'active' : '' }}">
                                        <a href="{{ route('purchase_inventory.all') }}"><i class="fa fa-circle-o"></i>Godown Stock</a>
                                    </li>
                                @endcan
                                @can('purchase_inventory')
                                    <li class="{{ Route::currentRouteName() == 'shop_purchase_inventory.all' ? 'active' : '' }}">
                                        <a href="{{ route('shop_purchase_inventory.all') }}"><i class="fa fa-circle-o"></i>Shop Stock</a>
                                    </li>
                                @endcan
                                @can('purchase_inventory')
                                    <li class="{{ Route::currentRouteName() == 'stock_transfer.invoice' ? 'active' : '' }}">
                                        <a href="{{ route('stock_transfer.invoice') }}"><i class="fa fa-circle-o"></i> Stock Transfer</a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                <?php
                $subMenu = ['sales_representative','sales_representative.add','sales_representative.edit',
                    'sr_product_assign.create','sr_product_assign.all','sr_product_assign.details','report.sr_sale']
                ?>
                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>SR System</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        <?php
                        $subSubMenu = ['sales_representative','sales_representative.add','sales_representative.edit']
                        ?>

                        <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                            <a href="{{ route('sales_representative') }}"><i class="fa fa-circle-o text-blue"></i> <span>SR</span></a>
                        </li>
                            <?php
                            $subSubMenu = ['sr_product_assign.create']
                            ?>

                            <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                <a href="{{ route('sr_product_assign.create') }}"><i class="fa fa-circle-o text-blue"></i> <span>SR Product Assign</span></a>
                            </li>
                            <?php
                            $subSubMenu = ['sr_product_assign.all','sr_product_assign.details']
                            ?>
                            <li class="{{ in_array(Route::currentRouteName(), $subSubMenu) ? 'active' : '' }}">
                                <a href="{{ route('sr_product_assign.all') }}"><i class="fa fa-circle-o text-blue"></i> <span>SR Product Assign List</span></a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'report.sr_sale' ? 'active' : '' }}">
                                <a href="{{ route('report.sr_sale') }}"><i class="fa fa-circle-o"></i>SR Sale Report</a>
                            </li>

                    </ul>
                </li>
                @endif
              @if( auth()->user()->role == \App\Enumeration\Role::$SR)
                <li class="{{ Route::currentRouteName() == 'sr_product_assign_list' ? 'active' : '' }}">
                    <a href="{{ route('sr_product_assign_list') }}">
                        <i class="fa fa-circle-o text-blue"></i> <span>Product Assign List</span>
                    </a>
                </li>
                    <li class="{{ Route::currentRouteName() == 'report.sr_sale' ? 'active' : '' }}">
                        <a href="{{ route('report.sr_sale') }}"><i class="fa fa-circle-o"></i>SR Sale Report</a>
                    </li>
                @endif

              @if(auth()->user()->role == \App\Enumeration\Role::$ADMIN)
            <?php
                $subMenu = ['sales_order.create', 'sale_receipt.all', 'sale_receipt.details',
                    'sale_receipt.payment_details', 'customer', 'customer.add',
                    'customer.edit', 'sale_information.index', 'customer_payment.all',
                    'sale_receipt.edit','report.sale','report.retailer'];
                ?>

                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Sale</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                        <li class="{{ Route::currentRouteName() == 'customer' ? 'active' : '' }}">
                            <a href="{{ route('customer') }}"><i class="fa fa-circle-o"></i> Retailer</a>
                        </li>


                        <li class="{{ Route::currentRouteName() == 'sales_order.create' ? 'active' : '' }}">
                            <a href="{{ route('sales_order.create') }}"><i class="fa fa-circle-o"></i> Sales Order</a>
                        </li>

                        <li class="{{ Route::currentRouteName() == 'report.sale' ? 'active' : '' }}">
                            <a href="{{ route('report.sale') }}"><i class="fa fa-circle-o"></i>Sale Report</a>
                        </li>
                        <!--<li class="{{ Route::currentRouteName() == 'report.retailer' ? 'active' : '' }}">-->
                        <!--    <a href="{{ route('report.retailer') }}"><i class="fa fa-circle-o"></i>Retailer Report</a>-->
                        <!--</li>-->
                    </ul>

                </li>
                @endif
{{--                @if(auth()->user()->role == \App\Enumeration\Role::$RETAILER)--}}
{{--                    <li class="{{ Route::currentRouteName() == 'report.sale' ? 'active' : '' }}">--}}
{{--                        <a href="{{ route('report.sale') }}"><i class="fa fa-circle-o"></i>Sale Report</a>--}}
{{--                    </li>--}}
{{--                @endif--}}
                @if(auth()->user()->role == \App\Enumeration\Role::$SR)
                    <li class="{{ Route::currentRouteName() == 'sr_sales_order.create' ? 'active' : '' }}">
                        <a href="{{ route('sr_sales_order.create') }}"><i class="fa fa-circle-o"></i>Sale Order</a>
                    </li>
                @endif


                @if(auth()->user()->role == \App\Enumeration\Role::$ADMIN)
                    <?php
                    $subMenu = ['customer_service','customer_service.add'];
                    ?>

                    <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o text-info"></i> <span>Customer Service</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">

                            <li class="{{ Route::currentRouteName() == 'customer_service' ? 'active' : '' }}">
                                <a href="{{ route('customer_service') }}"><i class="fa fa-circle-o"></i> Customer Service</a>
                            </li>


{{--                            <li class="{{ Route::currentRouteName() == 'sales_order.create' ? 'active' : '' }}">--}}
{{--                                <a href="{{ route('sales_order.create') }}"><i class="fa fa-circle-o"></i> Sales Order</a>--}}
{{--                            </li>--}}

{{--                            <li class="{{ Route::currentRouteName() == 'report.sale' ? 'active' : '' }}">--}}
{{--                                <a href="{{ route('report.sale') }}"><i class="fa fa-circle-o"></i>Sale Report</a>--}}
{{--                            </li>--}}

                        </ul>

                    </li>
                @endif
                @if(auth()->user()->role == \App\Enumeration\Role::$RETAILER)
                    <li class="{{ Route::currentRouteName() == 'report.sale' ? 'active' : '' }}">
                        <a href="{{ route('report.sale') }}"><i class="fa fa-circle-o"></i>Sale Report</a>
                    </li>
                @endif



            <?php
                $subMenu = ['account_head.type', 'account_head.type.add', 'account_head.type.edit',
                    'account_head.sub_type', 'account_head.sub_type.add', 'account_head.sub_type.edit',
                    'transaction.all', 'transaction.add', 'transaction.details'];
                ?>
                @if(auth()->user()->role == 1)
                @can('accounts')
                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Daily Accounts</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                        @can('account_head_type')
                        <li class="{{ Route::currentRouteName() == 'account_head.type' ? 'active' : '' }}">
                            <a href="{{ route('account_head.type') }}"><i class="fa fa-circle-o"></i> Category</a>
                        </li>
                        @endcan

                        @can('account_head_sub_type')
                        <li class="{{ Route::currentRouteName() == 'account_head.sub_type' ? 'active' : '' }}">
                            <a href="{{ route('account_head.sub_type') }}"><i class="fa fa-circle-o"></i> Sub Category</a>
                        </li>
                        @endcan

                        @can('transaction')
                        <li class="{{ Route::currentRouteName() == 'transaction.all' ? 'active' : '' }}">
                            <a href="{{ route('transaction.all') }}"><i class="fa fa-circle-o"></i> Income/ Expence</a>
                        </li>
                        @endcan


                    </ul>
                </li>
                @endcan

                <?php
                $subMenu = ['report.balance_sheet','report.stock_report',
                    'report.profit_and_loss', 'report.ledger', 'report.transaction','report.receive_and_payment'];
                ?>

                @can('report')
                <li class="treeview {{ in_array(Route::currentRouteName(), $subMenu) ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-circle-o text-info"></i> <span>Report</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu {{ in_array(Route::currentRouteName(), $subMenu) ? 'active menu-open' : '' }}">
                            @can('receive_and_payment_report')
                                <li class="{{ Route::currentRouteName() == 'report.receive_and_payment' ? 'active' : '' }}">
                                    <a href="{{ route('report.receive_and_payment') }}"><i class="fa fa-circle-o"></i> Receive & Payment</a>
                                </li>
                            @endcan
{{--                            @can('purchase_report')--}}
{{--                                <li class="{{ Route::currentRouteName() == 'report.stock_report' ? 'active' : '' }}">--}}
{{--                                    <a href="{{ route('report.stock_report') }}"><i class="fa fa-circle-o"></i> Stock Report</a>--}}
{{--                                </li>--}}
{{--                            @endcan--}}

                      @can('balance_sheet')
                        <li class="{{ Route::currentRouteName() == 'report.balance_sheet' ? 'active' : '' }}">
                            <a href="{{ route('report.balance_sheet') }}"><i class="fa fa-circle-o"></i> Balance Sheet</a>
                        </li>
                        @endcan

                        @can('profit_and_loss')
                        <li class="{{ Route::currentRouteName() == 'report.profit_and_loss' ? 'active' : '' }}">
                            <a href="{{ route('report.profit_and_loss') }}"><i class="fa fa-circle-o"></i> Profit & Loss</a>
                        </li>
                        @endcan

                        @can('ledger')
                        <li class="{{ Route::currentRouteName() == 'report.ledger' ? 'active' : '' }}">
                            <a href="{{ route('report.ledger') }}"><i class="fa fa-circle-o"></i> Ledger</a>
                        </li>
                        @endcan

                        @can('transaction_report')
                        <li class="{{ Route::currentRouteName() == 'report.transaction' ? 'active' : '' }}">
                            <a href="{{ route('report.transaction') }}"><i class="fa fa-circle-o"></i> Income/ Expense Report</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @endif
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('title')
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Design & Developed By <a target="_blank" href="http://2aitbd.com">2A IT</a></b>
        </div>
        <strong>Powered by Swarnaly Telecom. Help Line: +(888) 123-4567</strong>
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('themes/backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('themes/backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<!-- sweet alert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //Initialize Select2 Elements
        $('.select2').select2();
        //Date picker
        $('.date-picker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });
</script>

@yield('script')
<!-- AdminLTE App -->
<script src="{{ asset('themes/backend/js/adminlte.min.js') }}"></script>
</body>
</html>
