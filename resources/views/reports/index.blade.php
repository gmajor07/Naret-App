
@extends('layouts.master')


@section('content')
<br>
<div class="container">
    <h2>Generate Report</h2>

    <form action="{{ route('generateReports') }}" method="GET">
        @csrf

        <!-- Select Report Type -->
        <div class="form-group">
            <label for="report_type">Select Report Type:</label>
            <select id="report_type" name="report_type" class="form-control">
                <option value="">-- Select --</option>
                <option value="expenses">Expenses</option>
                <option value="revenue_no_vat">Revenue Without VAT</option>
                <option value="revenue_vat">Revenue with VAT</option>
                <option value="revenue">All Revenues</option>
            </select>
        </div>

        <!-- From Date (Hidden Initially) -->
        <div class="form-group" id="from_date_group" style="display: none;">
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date" class="form-control">
        </div>

        <!-- To Date (Hidden Initially) -->
        <div class="form-group" id="to_date_group" style="display: none;">
            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date" class="form-control">
        </div>

        <!-- Submit Button (Hidden Initially) -->
        <button type="submit" id="submit_btn" class="btn btn-primary" style="display: none;">Generate Report</button>
    </form>
</div>

<!-- jQuery for Dynamic Input Display -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
$(document).ready(function() {
    $("#report_type").change(function() {
        if ($(this).val() !== "") {
            $("#from_date_group").show();
        } else {
            $("#from_date_group, #to_date_group, #submit_btn").hide();
        }
    });

    $("#from_date").change(function() {
        if ($(this).val() !== "") {
            $("#to_date_group").show();
        }
    });

    $("#to_date").change(function() {
        if ($(this).val() !== "") {
            $("#submit_btn").show();
        }
    });
});
</script>
@endsection
