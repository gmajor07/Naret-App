@extends('layouts.master')

@section('content')
@php
    $chartRevenueTotal = collect($Current_salesData)->sum();
    $chartExpenseTotal = collect($current_expenseData)->sum();
    $chartNetTotal = $chartRevenueTotal - $chartExpenseTotal;
@endphp
<div class="dashboard-page">
    <div class="container-fluid">
        <div class="dashboard-hero">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="dashboard-hero__content">
                        <span class="dashboard-hero__eyebrow">
                            <i class="fas fa-chart-line"></i>
                            Admin dashboard
                        </span>
                        <h1 class="dashboard-hero__title">Welcome to Naret Company's control center.</h1>
                        <p class="dashboard-hero__subtitle">
                            Here you can see a modern business overview: monthly revenue, expenses, invoices, customers, and payment status with a clean, contemporary layout.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="dashboard-hero__meta">
                        <div class="dashboard-hero__meta-card">
                            <span class="dashboard-hero__meta-label">Monthly revenue</span>
                            <div class="dashboard-hero__meta-value">TZS {{ number_format($montly_revenue, 0, '.', ',') }}</div>
                            <span class="dashboard-hero__meta-note">{{ $currentYear }} business snapshot</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('expenses.index') }}" class="text-decoration-none">
                    <div class="dashboard-stat-card dashboard-stat-card--warning">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Expenses</div>
                                    <h3 class="dashboard-stat-card__value">TZS {{ number_format($total_expenses, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-donate"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">Current month expenses</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('orders.index', ['status' => 0]) }}" class="text-decoration-none">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Pending</div>
                                    <h3 class="dashboard-stat-card__value">{{ number_format($pending, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">All pending orders</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('orders.index', ['status' => 2]) }}" class="text-decoration-none">
                    <div class="dashboard-stat-card dashboard-stat-card--success">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Paid</div>
                                    <h3 class="dashboard-stat-card__value">{{ number_format($full_paid, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">All fully paid orders</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('orders.index', ['status' => 3]) }}" class="text-decoration-none">
                    <div class="dashboard-stat-card dashboard-stat-card--danger">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Cancelled</div>
                                    <h3 class="dashboard-stat-card__value">{{ number_format($cancelled, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-ban"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">All cancelled orders</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('customers.index') }}" class="text-decoration-none">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Customers</div>
                                    <h3 class="dashboard-stat-card__value">{{ number_format($customer_count, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">Total registered customers</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('users.index') }}" class="text-decoration-none">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Users</div>
                                    <h3 class="dashboard-stat-card__value">{{ number_format($user_count, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">System user accounts</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 mb-4">
                <div class="dashboard-panel dashboard-revenue-panel">
                    <div class="dashboard-panel__header dashboard-revenue-panel__header">
                        <div>
                            <span class="dashboard-revenue-panel__eyebrow">
                                <i class="fas fa-chart-area"></i>
                                Financial trend
                            </span>
                            <h3 class="dashboard-panel__title">Revenue vs Expenses</h3>
                            <p class="dashboard-panel__subtitle">{{ $chartSubtitle }}</p>
                        </div>
                        <div class="dashboard-revenue-panel__badge">
                            <span>{{ count($chartLabels) }}</span>
                            {{ count($chartLabels) === 1 ? 'period' : 'periods' }}
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-revenue-summary">
                            <div class="dashboard-revenue-summary__item dashboard-revenue-summary__item--revenue">
                                <span class="dashboard-revenue-summary__label">Revenue</span>
                                <strong>TZS {{ number_format($chartRevenueTotal, 0, '.', ',') }}</strong>
                            </div>
                            <div class="dashboard-revenue-summary__item dashboard-revenue-summary__item--expenses">
                                <span class="dashboard-revenue-summary__label">Expenses</span>
                                <strong>TZS {{ number_format($chartExpenseTotal, 0, '.', ',') }}</strong>
                            </div>
                            <div class="dashboard-revenue-summary__item {{ $chartNetTotal >= 0 ? 'dashboard-revenue-summary__item--profit' : 'dashboard-revenue-summary__item--loss' }}">
                                <span class="dashboard-revenue-summary__label">{{ $chartNetTotal >= 0 ? 'Net profit' : 'Net loss' }}</span>
                                <strong>TZS {{ number_format(abs($chartNetTotal), 0, '.', ',') }}</strong>
                            </div>
                        </div>

                        <form action="{{ route('admin') }}" method="GET" class="dashboard-date-filter">
                            <div class="dashboard-date-filter__grid">
                                <div class="dashboard-date-filter__field">
                                    <label for="chart_year">Year</label>
                                    <div class="dashboard-date-filter__control">
                                        <i class="fas fa-calendar-alt"></i>
                                        <select id="chart_year" name="chart_year" class="form-control">
                                            @foreach ($availableChartYears as $year)
                                                <option value="{{ $year }}" {{ (int) $selectedChartYear === (int) $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="dashboard-date-filter__field">
                                    <label for="chart_from_date">From date</label>
                                    <div class="dashboard-date-filter__control">
                                        <i class="fas fa-calendar-day"></i>
                                        <input type="date" id="chart_from_date" name="chart_from_date" class="form-control" value="{{ $chartFromDate }}">
                                    </div>
                                </div>
                                <div class="dashboard-date-filter__field">
                                    <label for="chart_to_date">To date</label>
                                    <div class="dashboard-date-filter__control">
                                        <i class="fas fa-calendar-check"></i>
                                        <input type="date" id="chart_to_date" name="chart_to_date" class="form-control" value="{{ $chartToDate }}">
                                    </div>
                                </div>
                                <div class="dashboard-date-filter__actions">
                                    <button type="submit" class="dashboard-date-filter__submit">
                                        <i class="fas fa-filter"></i>
                                        Apply
                                    </button>
                                    <a href="{{ route('admin') }}" class="dashboard-date-filter__reset" title="Reset chart dates">
                                        <i class="fas fa-redo-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="dashboard-chart-wrap">
                            <canvas
                                id="areaChart"
                                data-labels='{{ json_encode($chartLabels) }}'
                                data-sales='{{ json_encode($Current_salesData) }}'
                                data-expenses='{{ json_encode($current_expenseData) }}'
                            ></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Invoice payment status</h3>
                            <p class="dashboard-panel__subtitle">Overall progress for all issued and settled orders.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Total issued orders</span>
                                <span>{{ number_format($order_placed, 0, ',', ',') }}</span>
                            </div>
                            <div class="dashboard-progress">
                                <div class="dashboard-progress__bar dashboard-progress-target" data-width="100"></div>
                            </div>
                        </div>

                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Full paid orders</span>
                                <span>{{ number_format($full_paid, 0, ',', ',') }}/{{ $order_placed }}</span>
                            </div>
                            <div class="dashboard-progress">
                                <div class="dashboard-progress__bar dashboard-progress__bar--success dashboard-progress-target" data-width="{{ $order_placed ? ($full_paid / $order_placed) * 100 : 0 }}"></div>
                            </div>
                        </div>

                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Partial paid orders</span>
                                <span>{{ number_format($partial_paid, 0, ',', ',') }}/{{ $order_placed }}</span>
                            </div>
                            <div class="dashboard-progress">
                                <div class="dashboard-progress__bar dashboard-progress__bar--warning dashboard-progress-target" data-width="{{ $order_placed ? ($partial_paid / $order_placed) * 100 : 0 }}"></div>
                            </div>
                        </div>

                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Pending orders</span>
                                <span>{{ number_format($pending, 0, ',', ',') }}/{{ $order_placed }}</span>
                            </div>
                            <div class="dashboard-progress">
                                <div class="dashboard-progress__bar dashboard-progress__bar--muted dashboard-progress-target" data-width="{{ $order_placed ? ($pending / $order_placed) * 100 : 0 }}"></div>
                            </div>
                        </div>

                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Cancelled orders</span>
                                <span>{{ number_format($cancelled, 0, ',', ',') }}/{{ $order_placed }}</span>
                            </div>
                            <div class="dashboard-progress">
                                <div class="dashboard-progress__bar dashboard-progress__bar--danger dashboard-progress-target" data-width="{{ $order_placed ? ($cancelled / $order_placed) * 100 : 0 }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-7 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Business insights</h3>
                            <p class="dashboard-panel__subtitle">Important financial indicators for quick decision making.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-insight-grid">
                            <div class="dashboard-insight">
                                <span class="dashboard-insight__label">Monthly Revenue</span>
                                <div class="dashboard-insight__value">TZS {{ number_format($montly_revenue, 2, '.', ',') }}</div>
                                <div class="dashboard-insight__trend {{ $percentageIncrease >= 0 ? 'dashboard-insight__trend--success' : 'dashboard-insight__trend--danger' }}">
                                    <i class="fas {{ $percentageIncrease >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                    {{ $percentageIncrease }}% vs previous month
                                </div>
                            </div>

                            <div class="dashboard-insight">
                                <span class="dashboard-insight__label">Monthly Cost</span>
                                <div class="dashboard-insight__value">TZS {{ number_format($monthly_expenses, 2, '.', ',') }}</div>
                                <div class="dashboard-insight__trend">Operational expenses this month</div>
                            </div>

                            <div class="dashboard-insight">
                                <span class="dashboard-insight__label">{{ $montly_revenue >= $monthly_expenses ? 'Monthly Profit' : 'Monthly Loss' }}</span>
                                <div class="dashboard-insight__value">TZS {{ number_format(($montly_revenue - $monthly_expenses), 2, '.', ',') }}</div>
                                <div class="dashboard-insight__trend {{ $montly_revenue >= $monthly_expenses ? 'dashboard-insight__trend--success' : 'dashboard-insight__trend--danger' }}">
                                    {{ $montly_revenue >= $monthly_expenses ? 'Revenue is above expenses' : 'Expenses are above revenue' }}
                                </div>
                            </div>

                            <div class="dashboard-insight">
                                <span class="dashboard-insight__label">Annual Withholding Tax</span>
                                <div class="dashboard-insight__value">TZS {{ number_format($withholding, 2, '.', ',') }}</div>
                                <div class="dashboard-insight__trend">Captured from paid invoices</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Quick actions</h3>
                            <p class="dashboard-panel__subtitle">Fast access to the most important admin pages.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-quick-links">
                            <a href="{{ route('settings.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-cogs"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">System Settings</span>
                                    <span class="dashboard-quick-link__text">Manage users, roles, and access control</span>
                                </span>
                            </a>

                            <a href="{{ route('users.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-user-shield"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">User Management</span>
                                    <span class="dashboard-quick-link__text">Open the user administration page</span>
                                </span>
                            </a>

                            <a href="{{ route('unapprovedSales') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-hand-holding-usd"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Approve Sales</span>
                                    <span class="dashboard-quick-link__text">{{ $approveSales_count }} waiting for review</span>
                                </span>
                            </a>

                            <a href="{{ route('belowStockAvg') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-sort-amount-down"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Margin Alert</span>
                                    <span class="dashboard-quick-link__text">{{ $margin }} items below stock threshold</span>
                                </span>
                            </a>

                            <a href="{{ route('invoices.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-file-invoice"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Invoices</span>
                                    <span class="dashboard-quick-link__text">Manage billing and invoice records</span>
                                </span>
                            </a>

                            <a href="{{ route('reports') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-chart-pie"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Reports</span>
                                    <span class="dashboard-quick-link__text">Open exports and summary reports</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagescripts')
<script type="text/javascript">
    $(function () {
        var areaChartElement = $('#areaChart');
        var chartLabels = JSON.parse(areaChartElement.attr('data-labels') || '[]');
        var currArrayData = JSON.parse(areaChartElement.attr('data-sales') || '[]');
        var expenseData = JSON.parse(areaChartElement.attr('data-expenses') || '[]');
        var areaChartCanvas = areaChartElement.get(0).getContext('2d');
        var isDarkMode = document.body.classList.contains('dark-mode');
        var chartTextColor = isDarkMode ? '#cbd5e1' : '#667892';
        var chartTitleColor = isDarkMode ? '#e5eefc' : '#10233f';
        var chartGridColor = isDarkMode ? 'rgba(148, 163, 184, 0.14)' : 'rgba(15, 91, 216, 0.08)';

        $('.dashboard-progress-target').each(function () {
            var width = parseFloat($(this).attr('data-width') || '0');
            $(this).css('width', Math.max(0, Math.min(width, 100)) + '%');
        });

        var areaChartData = {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Revenue',
                    type: 'bar',
                    backgroundColor: 'rgba(39, 120, 240, 0.78)',
                    borderColor: 'rgba(15, 91, 216, 1)',
                    borderWidth: 0,
                    hoverBackgroundColor: 'rgba(15, 91, 216, 0.88)',
                    pointRadius: 0,
                    borderSkipped: false,
                    barPercentage: 0.72,
                    categoryPercentage: 0.58,
                    data: currArrayData
                },
                {
                    label: 'Expenses',
                    type: 'line',
                    backgroundColor: 'rgba(236, 95, 95, 0.10)',
                    borderColor: '#ec5f5f',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#ec5f5f',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#ec5f5f',
                    pointBorderWidth: 2,
                    lineTension: 0.35,
                    fill: true,
                    data: expenseData
                }
            ]
        };

        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(8, 42, 102, 0.94)',
                titleFontColor: '#ffffff',
                bodyFontColor: '#dbeafe',
                borderColor: 'rgba(255, 255, 255, 0.12)',
                borderWidth: 1,
                cornerRadius: 10,
                displayColors: true,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.datasets[tooltipItem.datasetIndex].label || '';
                        var value = Number(tooltipItem.yLabel || 0).toLocaleString();
                        return label + ': TZS ' + value;
                    }
                }
            },
            legend: {
                display: true,
                labels: {
                    fontColor: chartTitleColor,
                    boxWidth: 10,
                    padding: 18,
                    usePointStyle: true
                }
            },
            scales: {
                xAxes: [{
                    stacked: false,
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        fontColor: chartTextColor,
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 8
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: chartGridColor,
                        drawBorder: false,
                        zeroLineColor: 'rgba(15, 91, 216, 0.12)'
                    },
                    ticks: {
                        beginAtZero: true,
                        fontColor: chartTextColor,
                        callback: function(value) {
                            return Number(value).toLocaleString();
                        }
                    }
                }]
            }
        };

        new Chart(areaChartCanvas, {
            type: 'bar',
            data: areaChartData,
            options: areaChartOptions
        });
    });
</script>
@endsection
