@extends('layouts.master')

@section('content')


<br>
<div class="row">
    <div class="col-12 col-lg-12">
    <div class="card  card-outline card-tabs">

    <div class="card-header ">
        <h3 class="card-title"><i class="fas fa-users"></i> Customer Invoices</h3>
        @if (Auth::check() && Auth::user()->role_id == 1)
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#printInvoice" style="float:right" ><i class="fas fa-print"></i> Print </button>
        @endif
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block" id="success_element">
            <strong>{{ $message }}</strong>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Pending Invoice</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Partial Paid Invoice</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Paid Invoice</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Cancelled Invoice</a>
            </li>
            </ul>
    <div class="tab-content" id="custom-tabs-three-tabContent">
    <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
        <!-- unpaid invoice -->
        <br>
        <table id="example1" class="table table-bordered table-striped table-responsive-table" style="font-size: 14px;">
            <thead>
                <tr>
                   {{--  <th>S/N</th> --}}
                    <th>Invoice Number </th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Total Vat Inclusive</th>
                    <th>Vat</th>
                    <th>Discount</th>
                    <th>Totala Vat Exclusive</th>
                    <th>Amount Paid</th>
                    <th>Amount Due</th>
                    <th>Payment Status</th>
                    <th>Invoice Status</th>
                    <th>Due Date</th>
                    {{-- <th>Account for</th> --}}
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>

                @foreach ($pending_invoices as $key => $pending)
                    <tr>
                        {{-- <td> {{ ++$key }} </td> --}}
                        <td>{{ $pending->invoice_number }} </td>
                        <td> <a href="{{route('invoice.addPayment', $pending->id)}}"> {{ $pending->order->order_number}} </a></td> {{-- order number --}}
                        <td> {{ $pending->customer->name}} </td>
                        <td> {{number_format((  $pending->total_vat_inclusive),2,'.',',')}}</td>
                        <td> {{number_format(( $pending->vat),2,'.',',')}}</td>
                        <td> {{number_format(( $pending->discount),2,'.',',')}}</td>
                        <td> {{number_format(($pending->total_vat_exclusive),2,'.',',')}}</td>
                        <td> {{number_format(($pending->amount_paid),2,'.',',')}}</td>
                        <td>{{number_format(( $pending->amount_due),2,'.',',')}}</td>
                        @if ($pending->payment_status == 0)
                        <td class="text-warning">Waitinng for Payment..</td>
                        @elseif ($pending->payment_status == 1)
                        <td class="text-info">Partial Paid</td>
                        @elseif ($pending->payment_status == 2)
                        <td class="text-success"> Paid</td>
                        @else
                        <td class="text-danger">Cancelled</td>
                        @endif

                        @if ($pending->invoice_status == 0)
                        <td class="text-warning">Pending</td>
                        @elseif ($pending->invoice_status == 1)
                        <td class="text-info">Partial Paid</td>
                        @elseif ($pending->invoice_status == 2)
                        <td class="text-success">Paid</td>
                        @else
                        <td class="text-danger">Cancelled</td>
                        @endif
                        <td> {{ $pending->due_date}} </td>
                        {{-- <td> {{ $account->account_for}} </td> --}}
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                data-target="#modal-edit{{ $pending->id }}"><i class="fas fa-edit fa-xs"></i> </button>

                            <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                data-target="#modal-cancel{{ $pending->id }}"><i class="fas fas fa-ban">
                                </i></button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default">Action</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                @if ($pending->invoice_type == 1)
                                    <a class="dropdown-item" href="{{ route('printNaretInvoice', [$pending->id, 'profoma']) }}">Profoma Invoice</a>
                                    <a class="dropdown-item" href="{{ route('printNaretInvoice', [$pending->id, 'invoice']) }}">Invoice</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$pending->id, 'profoma']) }}">Profoma Invoice</a>
                                    <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$pending->id, 'invoice']) }}">Invoice</a>
                                @endif

                                <a class="dropdown-item" href="#">View</a>
                                <a class="dropdown-item" href="#">Edit</a>
                                <a class="dropdown-item" href="#">Cancel</a>
                            </div>
                        </div>

                        </td>
                    </tr>

                      <!-- cancel modal -->
                      <div class="modal fade" id="modal-cancel{{$pending->id}}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Cancelling Invoice {{$pending->invoice_number}} </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to cancel <b> {{$pending->invoice_number}}  </b> permanently? </p>
                                    You won't be able to revert this...!
                                </div>
                                <div class="modal-footer justify-content-between">

                                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                    <form action="{{route('invoiceCancell',$pending->id)}}" method="post">
                                        @csrf
                                        @method('Get')
                                        <button type="submit" class="btn btn-outline-danger">Yes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Editing User modal -->
                    <div class="modal fade" id="modal-edit{{$pending->id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form role="form" method="post" action="{{route('invoices.update',$pending->id)}}" id="activityForm">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title">Editing Account {{ $pending->invoice_number }} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between ">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="Submit" class="btn btn-outline-primary"> Update Invoice</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
        <!-- -->
    </div>
    <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
     <!-- partial paid -->
     <br>
     <table id="example2" class="table table-bordered table-striped table-responsive-table" style="font-size: 14px;">
        <thead>
            <tr>
                {{-- <th>S/N</th> --}}
                <th>Invoice Number</th>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Total Vat Inclusive</th>
                <th>Vat</th>
                <th>Discount</th>
                <th>Totala Vat Exclusive</th>
                <th>Amount Paid</th>
                <th>Amount Due</th>
                <th>Payment Status</th>
                <th>Invoice Status</th>
                <th>Due Date</th>
                <th width="10%"></th>

            </tr>
        </thead>
        <tbody>

            @foreach ($partial_paid_invoices as $key => $partial_paid)
                <tr>
                    {{-- <td> {{ ++$key }} </td> --}}
                    <td> <a href="{{route('invoice.addPayment', $partial_paid->id)}}"> {{ $partial_paid->invoice_number }} </a></td>
                    <td> {{ $partial_paid->order->order_number}} </td> {{-- order number --}}
                    <td> {{ $partial_paid->customer->name}} </td>
                    <td> {{number_format(( $partial_paid->total_vat_inclusive),2,'.',',')}}</td>
                    <td> {{number_format(( $partial_paid->vat),2,'.',',')}}</td>
                    <td> {{number_format(( $partial_paid->discount),2,'.',',')}}</td>
                    <td> {{number_format(( $partial_paid->total_vat_exclusive),2,'.',',')}}</td>
                    <td> {{number_format(( $partial_paid->amount_paid),2,'.',',')}}</td>
                    <td> {{number_format(( $partial_paid->amount_due),2,'.',',')}}</td>
                    @if ($partial_paid->payment_status == 0)
                    <td class="text-warning">Waitinng for Payment..</td>
                    @elseif ($partial_paid->payment_status == 1)
                    <td class="text-info">Partial Paid</td>
                    @elseif ($partial_paid->payment_status == 2)
                    <td class="text-success"> Paid</td>
                    @else
                    <td class="text-danger">Cancelled</td>
                    @endif


                    @if ($partial_paid->invoice_status == 0)
                    <td class="text-warning">Pending</td>
                    @elseif ($partial_paid->invoice_status == 1)
                    <td class="text-info">Partial Paid</td>
                    @elseif ($partial_paid->invoice_status == 2)
                    <td class="text-success">Paid</td>
                    @else
                    <td class="text-danger">Cancelled</td>
                    @endif
                    <td> {{ $partial_paid->due_date}} </td>
                    <td style="text-align: center;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default">Action</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                @if ($partial_paid->invoice_type == 1)
                                    <a class="dropdown-item" href="{{ route('printNaretInvoice', [$partial_paid->id, 'profoma']) }}">Profoma Invoice</a>
                                    <a class="dropdown-item" href="{{ route('printNaretInvoice', [$partial_paid->id, 'invoice']) }}">Invoice</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$partial_paid->id, 'profoma']) }}">Profoma Invoice</a>
                                    <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$partial_paid->id, 'invoice']) }}">Invoice</a>
                                @endif

                                <a class="dropdown-item" href="#">View</a>
                                <a class="dropdown-item" href="#">Edit</a>
                                <a class="dropdown-item" href="#">Cancel</a>
                            </div>
                        </div>
                    </td>

                </tr>

            @endforeach
        </tbody>
    </table>
     <!--end partial paid -->
    </div>
    <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
       <br>
       <!-- paid invoices -->
        <table id="paid" class="table table-bordered table-striped table-responsive-table" style="font-size: 14px;">
            <thead>
                <tr>
                    {{-- <th>S/N</th> --}}
                    <th>Invoice Number</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Total Vat Inclusive</th>
                    <th>Vat</th>
                    <th>Discount</th>
                    <th>Totala Vat Exclusive</th>
                    <th>Amount Paid</th>
                    <th>Amount Due</th>
                    <th>Payment Status</th>
                    <th>Invoice Status</th>
                    <th>Due Date</th>
                    <th width="10%"></th>

                </tr>
            </thead>
            <tbody>

                @foreach ($paid_invoices as $key => $paid)
                    <tr>
                        {{-- <td> {{ ++$key }} </td> --}}
                        <td> {{ $paid->invoice_number }} </td>
                        <td> {{ $paid->order->order_number}} </td> {{-- order number --}}
                        <td> {{ $paid->customer->name}} </td>
                        <td> {{number_format(( $paid->total_vat_inclusive),2,'.',',')}}</td>
                        <td> {{number_format(( $paid->vat),2,'.',',')}}</td>
                        <td> {{number_format(( $paid->discount),2,'.',',')}}</td>
                        <td> {{number_format(( $paid->total_vat_exclusive),2,'.',',')}}</td>
                        <td> {{number_format(( $paid->amount_paid),2,'.',',')}}</td>
                        <td> {{number_format(( $paid->amount_due),2,'.',',')}}</td>
                        @if ($paid->payment_status == 0)
                        <td class="text-warning">Waitinng for Payment..</td>
                        @elseif ($paid->payment_status == 1)
                        <td class="text-info">Partial Paid</td>
                        @elseif ($paid->payment_status == 2)
                        <td class="text-success"> Paid</td>
                        @else
                        <td class="text-danger">Cancelled</td>
                        @endif

                        @if ($paid->invoice_status == 0)
                        <td class="text-warning">Pending</td>
                        @elseif ($paid->invoice_status == 1)
                        <td class="text-info">Partial Paid</td>
                        @elseif ($paid->invoice_status == 2)
                        <td class="text-success">Paid</td>
                        @else
                        <td class="text-danger">Cancelled</td>
                        @endif
                        <td> {{ $paid->due_date}} </td>
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">Action</button>
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    @if ($paid->invoice_type == 1)
                                        <a class="dropdown-item" href="{{ route('printNaretInvoice', [$paid->id, 'profoma']) }}">Profoma Invoice</a>
                                        <a class="dropdown-item" href="{{ route('printNaretInvoice', [$paid->id, 'invoice']) }}">Invoice</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$paid->id, 'profoma']) }}">Profoma Invoice</a>
                                        <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$paid->id, 'invoice']) }}">Invoice</a>
                                    @endif

                                    <a class="dropdown-item" href="#">View</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Cancel</a>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
        <!--cancelled invoice -->
        <br>
        <table id="example4" class="table table-bordered table-striped table-responsive-table" style="font-size: 14px;">
            <thead>
                <tr>
                    {{-- <th>S/N</th> --}}
                    <th>Invoice Number</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Total Vat Inclusive</th>
                    <th>Vat</th>
                    <th>Discount</th>
                    <th>Totala Vat Exclusive</th>
                    <th>Amount Paid</th>
                    <th>Amount Due</th>
                    <th>Payment Status</th>
                    <th>Invoice Status</th>
                    <th>Due Date</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>

                @foreach ($cancelled_invoices as $key => $cancelled)
                    <tr>
                        {{-- <td> {{ ++$key }} </td> --}}
                        <td> {{ $cancelled->invoice_number }} </td>
                        <td> {{ $cancelled->order->order_number}} </td> {{-- order number --}}
                        <td> {{ $cancelled->customer->name}} </td>
                        <td> {{number_format(( $cancelled->total_vat_inclusive),2,'.',',')}}</td>
                        <td> {{number_format(( $cancelled->vat),2,'.',',')}}</td>
                        <td> {{number_format(( $cancelled->discount),2,'.',',')}}</td>
                        <td> {{number_format(( $cancelled->total_vat_exclusive),2,'.',',')}}</td>
                        <td> {{number_format(( $cancelled->amount_paid),2,'.',',')}}</td>
                        <td> {{number_format(( $cancelled->amount_due),2,'.',',')}}</td>
                        @if ($cancelled->payment_status == 0)
                        <td class="text-warning">Waitinng for Payment..</td>
                        @elseif ($cancelled->payment_status == 1)
                        <td class="text-info">Partial Paid</td>
                        @elseif ($cancelled->payment_status == 2)
                        <td class="text-success"> Paid</td>
                        @else
                        <td class="text-danger">Cancelled</td>
                        @endif

                        @if ($cancelled->invoice_status == 0)
                        <td class="text-warning">Pending</td>
                        @elseif ($cancelled->invoice_status == 1)
                        <td class="text-info">Partial Paid</td>
                        @elseif ($cancelled->invoice_status == 2)
                        <td class="text-success">Paid</td>
                        @else
                        <td class="text-danger">Cancelled</td>
                        @endif
                        <td> {{ $cancelled->due_date}} </td>
                        {{-- <td> {{ $account->account_for}} </td> --}}
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">Action</button>
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    @if ($cancelled->invoice_type == 1)
                                        <a class="dropdown-item" href="{{ route('printNaretInvoice', [$cancelled->id, 'profoma']) }}">Profoma Invoice</a>
                                        <a class="dropdown-item" href="{{ route('printNaretInvoice', [$cancelled->id, 'invoice']) }}">Invoice</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$cancelled->id, 'profoma']) }}">Profoma Invoice</a>
                                        <a class="dropdown-item" href="{{ route('printNaretFumigationInvonce', [$cancelled->id, 'invoice']) }}">Invoice</a>
                                    @endif

                                    <a class="dropdown-item" href="#">View</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Cancel</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                      <!-- delete modal -->
                      <div class="modal fade" id="modal-delete{{$cancelled->id}}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h4 class="modal-title">Deleting {{$cancelled->invoice_number}} </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <b> {{$cancelled->invoice_number}}  </b> permanently? </p>
                                    You won't be able to revert this...!
                                </div>
                                <div class="modal-footer justify-content-between">

                                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                    <form action="" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Yes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Editing User modal -->
                    <div class="modal fade" id="modal-edit{{$cancelled->id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form role="form" method="post" action="{{route('invoices.update',$cancelled->id)}}" id="activityForm">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title">Editing Account {{ $cancelled->invoice_number }} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between ">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="Submit" class="btn btn-outline-primary"> Update Invoice</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
        <!-- end cancelled -->
    </div>
    </div>
    </div>

     <!-- Generate report modal -->
     <div class="modal fade" id="printInvoice">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('printCustomInvoiceRecords')}}" id="activityForm" target="_blank">
                    @csrf
                    @method('GET')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Genarate Report</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Customer </label>
                                    <div class="select2-purple">
                                        <select class="select2" name="customer_id"  style="width: 100%;">
                                            <option value="" selected>Select a customer...</option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Invoice Status </label>
                                    <div class="select2-purple">
                                        <select class="select2" name="invoice_status"  style="width: 100%;">
                                            <option value="" selected>Select status...</option>
                                            <option value="2">Full Paid</option>
                                            <option value="1">Partial Paid</option>
                                            <option value="0">Pandeing</option>
                                            <option value="3">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">From<code>*</code></label>
                                    <input type="date" class="form-control" id="fromDate" placeholder="Enter start Date " name="from_date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">To<code>*</code></label>
                                    <input type="date" class="form-control" id="toDate" placeholder="Enter start Date " name="to_date">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





@endsection

{{-- page scripts --}}
@section('pagescripts')
    <!-- DataTables -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
             // Apply to all tables you want to be responsive
            $(".table-responsive-table").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
           /* $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
              $("#paid").DataTable({
                "responsive": true,
                "autoWidth": false,
            });*/
           /*  $("#example2").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
            $("#example3").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
            $("#example4").DataTable({
                "responsive": true,
                "autoWidth": false,
            });

           $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            }); */

            //Initialize Select2 Elements
            $('.select2').select2()
            setTimeout(function() {
                $("#success_element").hide();
            }, 2000);
        });



    // Use $(document).ready() to ensure the DOM is fully loaded
  // Define addProduct in the global scope

    </script>
@endsection



