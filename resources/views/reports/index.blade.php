@extends('layouts.master')

@section('content')
<style>
    .reports-page .dashboard-panel,
    .reports-page .dashboard-stat-card {
        height: 100%;
    }

    .reports-form-card {
        padding: 24px;
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(248, 251, 255, 0.98), rgba(255, 255, 255, 0.94));
        border: 1px solid rgba(15, 91, 216, 0.10);
    }

    .reports-form-card .form-group {
        margin-bottom: 18px;
    }

    .reports-form-card label {
        font-weight: 700;
        color: #16355f;
        margin-bottom: 8px;
    }

    .reports-form-card .form-control {
        height: 50px;
        border-radius: 14px;
        border: 1px solid rgba(15, 91, 216, 0.18);
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .reports-form-card select.form-control {
        background: transparent;
        color: #16355f;
        font-weight: 600;
        cursor: pointer;
    }

    .reports-form-card select.form-control:hover {
        border-color: rgba(15, 91, 216, 0.34);
        box-shadow: 0 10px 24px rgba(13, 62, 156, 0.08);
    }

    .reports-form-card .form-control:focus {
        border-color: #1d6fe3;
        box-shadow: 0 0 0 4px rgba(29, 111, 227, 0.12);
        transform: translateY(-1px);
    }

    .reports-form-card .btn {
        min-height: 50px;
        border-radius: 14px;
        padding: 0 22px;
        font-weight: 700;
        border: 0;
        box-shadow: 0 16px 30px rgba(8, 42, 102, 0.14);
    }

    .reports-form-card .btn-primary {
        background: linear-gradient(135deg, #0a4fc7 0%, #0d6efd 52%, #41abff 100%);
    }

    .reports-form-card .btn-primary:hover {
        filter: brightness(1.03);
    }

    .reports-helper-text {
        margin-top: 14px;
        color: #5f7391;
        font-size: 14px;
        line-height: 1.6;
    }

    .reports-step {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 18px;
        border-radius: 18px;
        background: var(--surface-secondary);
        border: 1px solid rgba(15, 91, 216, 0.08);
        margin-bottom: 14px;
    }

    .reports-step:last-child {
        margin-bottom: 0;
    }

    .reports-step__icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, 0.12);
        color: #0a4fc7;
        font-size: 17px;
    }

    .reports-step__title {
        display: block;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .reports-step__text {
        display: block;
        color: var(--text-secondary);
        font-size: 13px;
        line-height: 1.6;
    }

    .reports-export-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .reports-export-item {
        padding: 18px 20px;
        border-radius: 18px;
        background: var(--surface-secondary);
        border: 1px solid rgba(15, 91, 216, 0.08);
    }

    .reports-export-item__label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .reports-export-item__value {
        display: block;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .reports-hidden {
        display: none;
    }

    .reports-page .select2-container {
        width: 100% !important;
    }

    .reports-page .select2-container--default .select2-selection--single {
        height: 50px;
        border-radius: 14px;
        border: 1px solid rgba(15, 91, 216, 0.18);
        background: linear-gradient(180deg, #ffffff 0%, #f3f8ff 100%);
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .reports-page .select2-container--default:hover .select2-selection--single {
        border-color: rgba(15, 91, 216, 0.34);
        box-shadow: 0 10px 24px rgba(13, 62, 156, 0.08);
    }

    .reports-page .select2-container--default.select2-container--open .select2-selection--single,
    .reports-page .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #1d6fe3;
        box-shadow: 0 0 0 4px rgba(29, 111, 227, 0.12);
        transform: translateY(-1px);
    }

    .reports-page .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding-left: 16px;
        padding-right: 42px;
        color: #16355f;
        font-weight: 600;
    }

    .reports-page .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c7d96;
    }

    .reports-page .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
        right: 12px;
    }

    .reports-page .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #0a4fc7 transparent transparent transparent;
        border-width: 6px 5px 0 5px;
    }

    .reports-page .select2-dropdown {
        border: 1px solid rgba(15, 91, 216, 0.18);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 18px 40px rgba(8, 42, 102, 0.14);
    }

    .reports-page .select2-search--dropdown {
        padding: 10px;
        background: #f7faff;
        border-bottom: 1px solid rgba(15, 91, 216, 0.08);
    }

    .reports-page .select2-search--dropdown .select2-search__field {
        border-radius: 10px;
        border: 1px solid rgba(15, 91, 216, 0.16);
        padding: 8px 10px;
        color: #16355f;
        background: #ffffff;
    }

    .reports-page .select2-results__option {
        padding: 10px 14px;
        color: #16355f;
        font-weight: 600;
        background: #ffffff;
    }

    .reports-page .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #0a4fc7 0%, #2b7ef0 100%);
        color: #ffffff;
    }

    .reports-page .select2-container--default .select2-results__option[aria-selected=true] {
        background: #eaf2ff;
        color: #0a4fc7;
    }

    body.dashboard-modern.dark-mode .reports-form-card,
    body.dark-mode.dashboard-modern .reports-form-card {
        background: linear-gradient(180deg, rgba(21, 34, 59, 0.96), rgba(14, 24, 44, 0.96));
        border-color: rgba(120, 163, 255, 0.14);
    }

    body.dashboard-modern.dark-mode .reports-form-card label,
    body.dark-mode.dashboard-modern .reports-form-card label {
        color: #dbe8ff;
    }

    body.dashboard-modern.dark-mode .reports-helper-text,
    body.dark-mode.dashboard-modern .reports-helper-text {
        color: #96a9c9;
    }

    body.dashboard-modern.dark-mode .reports-page .select2-container--default .select2-selection--single,
    body.dark-mode.dashboard-modern .reports-page .select2-container--default .select2-selection--single {
        border-color: rgba(120, 163, 255, 0.16);
        background: linear-gradient(180deg, #182846 0%, #14213a 100%);
    }

    body.dashboard-modern.dark-mode .reports-page .select2-container--default:hover .select2-selection--single,
    body.dark-mode.dashboard-modern .reports-page .select2-container--default:hover .select2-selection--single {
        border-color: rgba(120, 163, 255, 0.32);
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.22);
    }

    body.dashboard-modern.dark-mode .reports-page .select2-container--default .select2-selection--single .select2-selection__rendered,
    body.dark-mode.dashboard-modern .reports-page .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #eef4ff;
    }

    body.dashboard-modern.dark-mode .reports-page .select2-container--default .select2-selection--single .select2-selection__placeholder,
    body.dark-mode.dashboard-modern .reports-page .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #90a4c5;
    }

    body.dashboard-modern.dark-mode .reports-page .select2-container--default .select2-selection--single .select2-selection__arrow b,
    body.dark-mode.dashboard-modern .reports-page .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #8ec5ff transparent transparent transparent;
    }

    body.dashboard-modern.dark-mode .reports-page .select2-dropdown,
    body.dark-mode.dashboard-modern .reports-page .select2-dropdown {
        border-color: rgba(120, 163, 255, 0.16);
        box-shadow: 0 20px 44px rgba(0, 0, 0, 0.34);
    }

    body.dashboard-modern.dark-mode .reports-page .select2-search--dropdown,
    body.dark-mode.dashboard-modern .reports-page .select2-search--dropdown {
        background: #16233f;
        border-bottom-color: rgba(120, 163, 255, 0.10);
    }

    body.dashboard-modern.dark-mode .reports-page .select2-search--dropdown .select2-search__field,
    body.dark-mode.dashboard-modern .reports-page .select2-search--dropdown .select2-search__field {
        background: #0f1b31;
        border-color: rgba(120, 163, 255, 0.16);
        color: #eef4ff;
    }

    body.dashboard-modern.dark-mode .reports-page .select2-results__option,
    body.dark-mode.dashboard-modern .reports-page .select2-results__option {
        background: #14213a;
        color: #e6efff;
    }

    body.dashboard-modern.dark-mode .reports-page .select2-container--default .select2-results__option[aria-selected=true],
    body.dark-mode.dashboard-modern .reports-page .select2-container--default .select2-results__option[aria-selected=true] {
        background: #1d335a;
        color: #8ec5ff;
    }

    @media (max-width: 767.98px) {
        .reports-form-card {
            padding: 18px;
        }

        .reports-export-list {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-page reports-page">
    <div class="container-fluid">
        <div class="dashboard-hero">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="dashboard-hero__content">
                        <span class="dashboard-hero__eyebrow">
                            <i class="fas fa-file-export"></i>
                            Reports center
                        </span>
                        <h1 class="dashboard-hero__title">Generate business reports with a cleaner workflow.</h1>
                        <p class="dashboard-hero__subtitle">
                            Chagua aina ya report, weka date range, kisha system itakutengenezea export ya Excel
                            iliyo tayari kwa review, sharing, au record keeping.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="dashboard-hero__meta">
                        <div class="dashboard-hero__meta-card">
                            <span class="dashboard-hero__meta-label">Available exports</span>
                            <div class="dashboard-hero__meta-value">4</div>
                            <span class="dashboard-hero__meta-note">Expenses, revenue, VAT, and non-VAT summaries</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-xl-8 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Generate report</h3>
                            <p class="dashboard-panel__subtitle">Fill in the fields below and download the export instantly.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="reports-form-card">
                            <form action="{{ route('generateReports') }}" method="GET">
                                @csrf

                                <div class="form-group">
                                    <label for="report_type">Select report type</label>
                                    <select id="report_type" name="report_type" class="form-control report-select2" required>
                                        <option value=""></option>
                                        <option value="expenses" {{ request('report_type') === 'expenses' ? 'selected' : '' }}>Expenses</option>
                                        <option value="revenue_no_vat" {{ request('report_type') === 'revenue_no_vat' ? 'selected' : '' }}>Revenue Without VAT</option>
                                        <option value="revenue_vat" {{ request('report_type') === 'revenue_vat' ? 'selected' : '' }}>Revenue with VAT</option>
                                        <option value="revenue" {{ request('report_type') === 'revenue' ? 'selected' : '' }}>All Revenues</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ request('report_type') ? '' : 'reports-hidden' }}" id="from_date_group">
                                            <label for="from_date">From date</label>
                                            <input
                                                type="date"
                                                id="from_date"
                                                name="from_date"
                                                class="form-control"
                                                value="{{ request('from_date') }}"
                                                {{ request('report_type') ? 'required' : '' }}
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ request('from_date') ? '' : 'reports-hidden' }}" id="to_date_group">
                                            <label for="to_date">To date</label>
                                            <input
                                                type="date"
                                                id="to_date"
                                                name="to_date"
                                                class="form-control"
                                                value="{{ request('to_date') }}"
                                                {{ request('from_date') ? 'required' : '' }}
                                            >
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    id="submit_btn"
                                    class="btn btn-primary {{ request('to_date') ? '' : 'reports-hidden' }}"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Generate Report
                                </button>

                                <p class="reports-helper-text">
                                    Tip: choose a valid date range so the downloaded spreadsheet matches the exact reporting period you need.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">How it works</h3>
                            <p class="dashboard-panel__subtitle">Simple steps for a fast export workflow.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="reports-step">
                            <span class="reports-step__icon"><i class="fas fa-list-ul"></i></span>
                            <span>
                                <span class="reports-step__title">1. Pick the report type</span>
                                <span class="reports-step__text">Select the business area you want to export.</span>
                            </span>
                        </div>

                        <div class="reports-step">
                            <span class="reports-step__icon"><i class="fas fa-calendar-alt"></i></span>
                            <span>
                                <span class="reports-step__title">2. Set the date range</span>
                                <span class="reports-step__text">Choose start and end dates for the reporting period.</span>
                            </span>
                        </div>

                        <div class="reports-step">
                            <span class="reports-step__icon"><i class="fas fa-file-excel"></i></span>
                            <span>
                                <span class="reports-step__title">3. Download the file</span>
                                <span class="reports-step__text">Your Excel export will be generated immediately after submission.</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="dashboard-panel">
                    <div class="dashboard-panel__header">
                        <div>
                            <h3 class="dashboard-panel__title">Export options</h3>
                            <p class="dashboard-panel__subtitle">Available report categories in this screen.</p>
                        </div>
                    </div>
                    <div class="dashboard-panel__body">
                        <div class="reports-export-list">
                            <div class="reports-export-item">
                                <span class="reports-export-item__label">Financial cost</span>
                                <span class="reports-export-item__value">Expenses report</span>
                            </div>
                            <div class="reports-export-item">
                                <span class="reports-export-item__label">Sales summary</span>
                                <span class="reports-export-item__value">All revenues report</span>
                            </div>
                            <div class="reports-export-item">
                                <span class="reports-export-item__label">Tax separated</span>
                                <span class="reports-export-item__value">Revenue with VAT</span>
                            </div>
                            <div class="reports-export-item">
                                <span class="reports-export-item__label">Tax excluded</span>
                                <span class="reports-export-item__value">Revenue without VAT</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagescripts')
<script>
    $(function () {
        var $reportType = $('#report_type');
        var $fromDate = $('#from_date');
        var $toDate = $('#to_date');
        var $fromGroup = $('#from_date_group');
        var $toGroup = $('#to_date_group');
        var $submitBtn = $('#submit_btn');

        $reportType.select2({
            placeholder: 'Choose a report',
            minimumResultsForSearch: Infinity,
            width: '100%',
            dropdownCssClass: 'reports-select-dropdown'
        });

        function toggleReportFields() {
            var hasType = $reportType.val() !== '';
            var hasFromDate = $fromDate.val() !== '';
            var hasToDate = $toDate.val() !== '';

            $fromGroup.toggleClass('reports-hidden', !hasType);
            $toGroup.toggleClass('reports-hidden', !hasFromDate);
            $submitBtn.toggleClass('reports-hidden', !hasToDate);

            $fromDate.prop('required', hasType);
            $toDate.prop('required', hasFromDate);
        }

        $reportType.on('change', function () {
            if (!$reportType.val()) {
                $fromDate.val('');
                $toDate.val('');
            }

            toggleReportFields();
        });

        $fromDate.on('change', function () {
            if (!$fromDate.val()) {
                $toDate.val('');
            }

            toggleReportFields();
        });

        $toDate.on('change', toggleReportFields);

        toggleReportFields();
    });
</script>
@endsection
