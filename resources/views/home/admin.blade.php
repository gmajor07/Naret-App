@extends('layouts.master')

@section('content')
<br>


<section class="content ">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('users.index')}}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">User Management</span>
                            <span class="info-box-number">
                               @if ($user_count > 0)
                               ({{$user_count}})
                               @endif
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{route('unapprovedSales')}}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approve Sales</span>
                            <span class="info-box-number">
                               @if ($approveSales_count > 0)
                               ({{$approveSales_count}})
                               @endif
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{route('invoices.index')}}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Invoices</span>
                            <span class="info-box-number">

                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('belowStockAvg')}}">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas  fa-sort-amount-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Margin notification</span>
                            <span class="info-box-number">
                             @if ($margin > 0)
                             ({{$margin }})
                             @endif

                            </span>
                        </div>
                    </div>
                </a>
            </div>


        </div>
    {{-- ################################################## --}}
    <div class="row ">
        <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
            <h5 class="card-title">Monthly Recap Report</h5>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
                <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-wrench"></i>
                </button>
            {{--    <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a href="#" class="dropdown-item">Action</a>
                    <a href="#" class="dropdown-item">Another action</a>
                    <a href="#" class="dropdown-item">Something else here</a>
                    <a class="dropdown-divider"></a>
                    <a href="#" class="dropdown-item">Separated link</a>
                </div> --}}
                </div>
            {{--  <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
                </button> --}}
            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                <p class="text-center">
                    <strong>Sales: 1 Jan, {{$currentYear}} - 31 Dec, {{$currentYear}}</strong>
                </p>

                <div class="chart " id="revenue-chart"
                       style="position: relative; height: 300px;">
                    <!-- Sales Chart Canvas -->
                    <canvas id="areaChart" height="180" style="height: 180px;"></canvas>
                </div>
                <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                <p class="text-center">
                    <strong>Monthly Goal Completion</strong>
                </p>

                <div class="progress-group">
                    Total Issued Invoice
                    <span class="float-right"><b>{{ number_format($order_placed,0,',')}}</b>/100</span>
                    <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: {{($order_placed/100)*100}}%"></div>
                    </div>
                </div>
                <!-- /.progress-group -->

                <div class="progress-group">
                    Full Paid Invoice
                    <span class="float-right"><b>{{ number_format($full_paid, 0, ',') }}</b>/{{ $order_placed }}</span>
                    <div class="progress progress-sm">
                        @if($order_placed != 0)
                            <div class="progress-bar bg-success" style="width: {{ ($full_paid / $order_placed) * 100 }}%" ></div>
                        @else
                            <div class="progress-bar " style="width: 0%"></div>
                        @endif
                    </div>
                </div>
                <!-- /.progress-group -->

                <div class="progress-group">
                    Partial Paid Invoice
                    <span class="float-right"><b>{{ number_format($partial_paid,0,',')}}</b>/{{$order_placed}}</span>
                    <div class="progress progress-sm">
                    @if($order_placed != 0)
                        <div class="progress-bar bg-warning" style="width: {{ ($partial_paid / $order_placed) * 100 }}%" ></div>
                    @else
                        <div class="progress-bar " style="width: 0%"></div>
                @endif
                    </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                    <span class="progress-text">Pending Invoice</span>
                    <span class="float-right"><b>{{ number_format($pending,0,',')}}</b>/{{$order_placed}}</span>
                    <div class="progress progress-sm">
                    @if($order_placed != 0)
                            <div class="progress-bar bg-secondary" style="width: {{ ($pending / $order_placed) * 100 }}%" ></div>
                        @else
                            <div class="progress-bar " style="width: 0%"></div>
                        @endif
                    </div>
                </div>

                <!-- /.progress-group -->
                <div class="progress-group">
                    Cancelled Invoice
                    <span class="float-right"><b>{{ number_format($cancelled,0,',')}}</b>/{{$order_placed}}</span>
                    <div class="progress progress-sm">
                        @if($order_placed != 0)
                        <div class="progress-bar bg-danger" style="width: {{ ($cancelled / $order_placed) * 100 }}%" ></div>
                    @else
                        <div class="progress-bar " style="width: 0%"></div>
                    @endif
                    </div>
                </div>
                <!-- /.progress-group -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
            <div class="row">
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    @if($percentageIncrease >= 0 )
                        <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> {{$percentageIncrease}}%</span>

                    @else
                        <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> {{$percentageIncrease}}%</span>

                    @endif
                    <h5 class="description-header">TZs. {{number_format(( $montly_revenue),2,'.',',')}}</h5>
                    <span class="description-text">MONTHLY TOTAL REVENUE</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                    <h5 class="description-header">TZs. {{number_format(( $monthly_expenses),2,'.',',')}}</h5>
                    <span class="description-text">MONTHLY TOTAL COST</span>
                </div>
                <!-- /.description-block -->
                </div>
                @if($montly_revenue > $monthly_expenses)
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                    <h5 class="description-header">TZs. {{number_format(( $montly_revenue - $monthly_expenses),2,'.',',')}}</h5>
                    <span class="description-text">MONTHLY PROFIT</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                @elseif ($montly_revenue < $monthly_expenses)
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 20%</span>
                    <h5 class="description-header text-danger">TZs. {{number_format(( $montly_revenue - $monthly_expenses),2,'.',',')}}</h5>
                    <span class="description-text text-danger">MONTHLY LOSS</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                @else
                 <!-- /.col -->
                 <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                        <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 0%</span>
                        <h5 class="description-header">TZs. {{number_format((000),2,'.',',')}}</h5>
                        <span class="description-text">MONTHLY PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                @endif
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> %</span>
                    <h5 class="description-header">TZs. {{number_format($withholding,2,'.',',')}}</h5>
                    <span class="description-text">ANNUAL WITHHOLDING TAX</span>
                </div>
                <!-- /.description-block -->
                </div>
            </div>
            <!-- /.row -->

            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    {{-- #################################################### --}}

    </div>
</section>

@endsection

@section('pagescripts')

<script type="text/javascript">
    $(function () {
        /* ChartJS
        * -------
        * Here we will create a few charts using ChartJS
        */
        // Get context with jQuery - using jQuery's .get() method.

        var currArrayData = {!! json_encode($Current_salesData) !!};
        var expenseData = {!! json_encode($current_expenseData) !!};
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d');


        var areaChartData = {
            labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                       'August','September','October','November','December'],
            datasets: [
                {
                    label               : 'Sales',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : currArrayData
                },
                {
                    label               : 'Expenses',
                    backgroundColor     : 'rgba(210, 214, 222, 1)',
                    borderColor         : 'rgba(210, 214, 222, 1)',
                    pointRadius         : false,
                    pointColor          : 'rgba(210, 214, 222, 1)',
                    pointStrokeColor    : '#c1c7d1',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data                : expenseData
                } // Closing square bracket was missing here
            ]
        };

        var areaChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    gridLines : {
                        display : true,
                    }
                }],
                yAxes: [{
                    gridLines : {
                        display : true,
                    },
                    ticks: {
                    beginAtZero: true, // Ensures the y-axis starts at zero
                   //min: 0 // Ensures the minimum value is zero
                   callback: function(value, index, values) {
                            return value.toLocaleString();
                        }
                }
                }]
            }
        };

        // This will get the first returned node in the jQuery collection.
        new Chart(areaChartCanvas, {
            type: 'bar',
            data: areaChartData,
            options: areaChartOptions
        });
    });
</script>

@endsection
