@extends('layouts.master')

@section('content')
<div class="dashboard-page">
    <div class="container-fluid">
        <div class="dashboard-hero">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="dashboard-hero__content">
                        <span class="dashboard-hero__eyebrow">
                            <i class="fas fa-briefcase"></i>
                            Seller dashboard
                        </span>
                        <h1 class="dashboard-hero__title">Karibu kwenye seller workspace ya Naret Company.</h1>
                        <p class="dashboard-hero__subtitle">
                            Tanzanian company bringing a strong legacy of excellence, with highly experienced professionals, specializing In wide range of Miscellaneous Port operations delivering multiple efficient services


                        </p>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="dashboard-hero__meta">
                        <div class="dashboard-hero__meta-card">
                            <span class="dashboard-hero__meta-label">Customers</span>
                            <div class="dashboard-hero__meta-value">{{ number_format($customer_count, 0, '.', ',') }}</div>
                            <span class="dashboard-hero__meta-note">Total registered customers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-2 mb-4">
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
                            <div class="dashboard-stat-card__meta">Total customer count</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xl-2 mb-4">
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

            <div class="col-md-6 col-xl-2 mb-4">
                <a href="{{ route('allRejectedSales') }}" class="text-decoration-none">
                    <div class="dashboard-stat-card dashboard-stat-card--danger">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Rejected Sales</div>
                                    <h3 class="dashboard-stat-card__value">{{ number_format($rejected, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">Review and update rejected items</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-xl-2 mb-4">
                <a href="{{ route('orders.index') }}" class="text-decoration-none">
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

            <div class="col-md-6 col-xl-2 mb-4">
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

            <div class="col-md-6 col-xl-2 mb-4">
                <a href="{{ route('expenses.index') }}" class="text-decoration-none">
                    <div class="dashboard-stat-card dashboard-stat-card--warning">
                        <div class="dashboard-stat-card__body">
                            <div class="dashboard-stat-card__top">
                                <div>
                                    <div class="dashboard-stat-card__label">Expenses</div>
                                    <h3 class="dashboard-stat-card__value">TZS {{ number_format($total_expenses, 0, '.', ',') }}</h3>
                                </div>
                                <span class="dashboard-stat-card__icon">
                                    <i class="fas fa-wallet"></i>
                                </span>
                            </div>
                            <div class="dashboard-stat-card__meta">All time total expenses</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-7 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Quick actions</h3>
                            <p class="dashboard-panel__subtitle">Common workflows for your daily sales and service operations.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-quick-links">
                            <a href="{{ route('assignView') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-user-check"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Assign a Fumigator</span>
                                    <span class="dashboard-quick-link__text">Assign jobs quickly to fumigators</span>
                                </span>
                            </a>

                            <a href="{{ route('customers.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-users"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Customers</span>
                                    <span class="dashboard-quick-link__text">Manage customer information</span>
                                </span>
                            </a>

                            <a href="{{ route('orders.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-shopping-basket"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Product Orders</span>
                                    <span class="dashboard-quick-link__text">Create and monitor orders</span>
                                </span>
                            </a>

                            <a href="{{ route('fumigation.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-air-freshener"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Fumigation Service</span>
                                    <span class="dashboard-quick-link__text">Handle fumigation requests and service flow</span>
                                </span>
                            </a>

                            <a href="{{ route('expenses.index') }}" class="dashboard-quick-link">
                                <span class="dashboard-quick-link__icon"><i class="fas fa-wallet"></i></span>
                                <span>
                                    <span class="dashboard-quick-link__title">Expenses</span>
                                    <span class="dashboard-quick-link__text">Review operational spending records</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Attention area</h3>
                            <p class="dashboard-panel__subtitle">Main point that may need follow-up today.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-insight-grid">
                            <div class="dashboard-insight">
                                <span class="dashboard-insight__label">Rejected Sales</span>
                                <div class="dashboard-insight__value">{{ number_format($rejected, 0, '.', ',') }}</div>
                                <div class="dashboard-insight__trend {{ $rejected > 0 ? 'dashboard-insight__trend--danger' : 'dashboard-insight__trend--success' }}">
                                    {{ $rejected > 0 ? 'Review and resubmit affected sales' : 'No rejected sales at the moment' }}
                                </div>
                            </div>

                            <div class="dashboard-insight">
                                <span class="dashboard-insight__label">Workflow Focus</span>
                                <div class="dashboard-insight__value">Fast response</div>
                                <div class="dashboard-insight__trend">Keep orders, invoices and assignments moving smoothly</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
