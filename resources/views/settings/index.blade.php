@extends('layouts.master')

@section('content')
<div class="dashboard-page">
    <div class="container-fluid">
        <div class="dashboard-hero">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="dashboard-hero__content">
                        <span class="dashboard-hero__eyebrow">
                            <i class="fas fa-cogs"></i>
                            System settings
                        </span>
                        <h1 class="dashboard-hero__title">Manage users and system access.</h1>
                        <p class="dashboard-hero__subtitle">
                            Hapa ndipo administrator anasimamia system users: kuongeza user, kubadilisha role,
                            ku-reset password, ku-activate au ku-deactivate account, na kufuta user asiyemtaka.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="dashboard-hero__meta">
                        <div class="dashboard-hero__meta-card">
                            <span class="dashboard-hero__meta-label">Access summary</span>
                            <div class="dashboard-hero__meta-value">{{ number_format($activeUsers, 0, '.', ',') }}</div>
                            <span class="dashboard-hero__meta-note">Active accounts in the system</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-4 mb-4">
                <div class="dashboard-stat-card">
                    <div class="dashboard-stat-card__body">
                        <div class="dashboard-stat-card__top">
                            <div>
                                <div class="dashboard-stat-card__label">Total users</div>
                                <h3 class="dashboard-stat-card__value">{{ number_format($totalUsers, 0, '.', ',') }}</h3>
                            </div>
                            <span class="dashboard-stat-card__icon">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <div class="dashboard-stat-card__meta">All registered system accounts</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-4">
                <div class="dashboard-stat-card dashboard-stat-card--success">
                    <div class="dashboard-stat-card__body">
                        <div class="dashboard-stat-card__top">
                            <div>
                                <div class="dashboard-stat-card__label">Administrators</div>
                                <h3 class="dashboard-stat-card__value">{{ number_format($adminUsers, 0, '.', ',') }}</h3>
                            </div>
                            <span class="dashboard-stat-card__icon">
                                <i class="fas fa-user-shield"></i>
                            </span>
                        </div>
                        <div class="dashboard-stat-card__meta">Users with full system control</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-4">
                <div class="dashboard-stat-card dashboard-stat-card--warning">
                    <div class="dashboard-stat-card__body">
                        <div class="dashboard-stat-card__top">
                            <div>
                                <div class="dashboard-stat-card__label">Active users</div>
                                <h3 class="dashboard-stat-card__value">{{ number_format($activeUsers, 0, '.', ',') }}</h3>
                            </div>
                            <span class="dashboard-stat-card__icon">
                                <i class="fas fa-user-check"></i>
                            </span>
                        </div>
                        <div class="dashboard-stat-card__meta">Users who can log in right now</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">User administration</h3>
                            <p class="dashboard-panel__subtitle">Open the page below to manage all system users and their roles.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <a href="{{ route('users.index') }}" class="dashboard-quick-link">
                            <span class="dashboard-quick-link__icon"><i class="fas fa-user-shield"></i></span>
                            <span>
                                <span class="dashboard-quick-link__title">User Management</span>
                                <span class="dashboard-quick-link__text">Add, edit, assign role, reset password, activate, deactivate, or delete users</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Important note</h3>
                            <p class="dashboard-panel__subtitle">Safety rules for system owner accounts.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Admin accounts cannot delete themselves</span>
                            </div>
                        </div>
                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>The last administrator cannot be deleted</span>
                            </div>
                        </div>
                        <div class="dashboard-progress-card">
                            <div class="dashboard-progress-card__top">
                                <span>Deactivated users cannot log in</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
