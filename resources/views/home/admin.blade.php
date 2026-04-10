@extends('layouts.master')

@section('content')
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
                            <div class="dashboard-stat-card__meta">All time total expenses</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-2 mb-4">
                <a href="{{ route('orders.index') }}" class="text-decoration-none">
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
                <a href="{{ route('paidInvoices') }}" class="text-decoration-none">
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
                <a href="{{ route('orders.index') }}" class="text-decoration-none">
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
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Revenue vs Expenses</h3>
                            <p class="dashboard-panel__subtitle">
                                Sales and expenses overview from January to December {{ $currentYear }}.
                            </p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-chart-wrap">
                            <canvas
                                id="areaChart"
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
        var currArrayData = JSON.parse(areaChartElement.attr('data-sales') || '[]');
        var expenseData = JSON.parse(areaChartElement.attr('data-expenses') || '[]');
        var areaChartCanvas = areaChartElement.get(0).getContext('2d');

        $('.dashboard-progress-target').each(function () {
            var width = parseFloat($(this).attr('data-width') || '0');
            $(this).css('width', Math.max(0, Math.min(width, 100)) + '%');
        });

        var areaChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                    label: 'Sales',
                    backgroundColor: 'rgba(39, 120, 240, 0.75)',
                    borderColor: 'rgba(15, 91, 216, 1)',
                    borderWidth: 1,
                    pointRadius: 4,
                    pointBackgroundColor: '#0f5bd8',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#0f5bd8',
                    data: currArrayData
                },
                {
                    label: 'Expenses',
                    backgroundColor: 'rgba(102, 120, 146, 0.45)',
                    borderColor: 'rgba(102, 120, 146, 0.95)',
                    borderWidth: 1,
                    pointRadius: 4,
                    pointBackgroundColor: '#667892',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#667892',
                    data: expenseData
                }
            ]
        };

        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true,
                labels: {
                    fontColor: '#10233f'
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        fontColor: '#667892'
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: 'rgba(15, 91, 216, 0.08)'
                    },
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#667892',
                        callback: function(value) {
                            return value.toLocaleString();
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
