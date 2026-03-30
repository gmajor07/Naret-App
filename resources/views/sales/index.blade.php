
@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Sales Record</h3>
        </div>
        <!-- /.card-header -->
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

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        {{-- <th>S/N</th> --}}
                        <th>Order Number</th>
                        <th>Customer Name</th>
                        <th>Invoice Number</th>
                        <th>Sales Amount</th>
                        <th>Received Date</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($sales as $key => $sale)
                        <tr>
                            {{-- <td> {{ ++$key }} </td> --}}
                            <td> {{ $sale->order->order_number }} </td>
                            <td> {{ $sale->customer->name}} </td>
                            <td> {{ $sale->invoice->invoice_number}} </td>
                            <td> {{number_format(( $sale->total_amount),2,'.',',')}}</td>
                            <td> {{\Carbon\Carbon::parse($sale->updated_at)->format('d-m-Y')}}</td>

                        </tr>

                    @endforeach
                </tbody>
            </table>

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

    <script type="text/javascript">
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });


        });

    </script>
@endsection
