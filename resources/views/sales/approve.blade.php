
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
            @if ($message = Session::get('failure'))
            <div class="alert alert-danger alert-block" id="failure_element">
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($unapproved as $key => $sale)
                        <tr>
                            {{-- <td> {{ ++$key }} </td> --}}
                            <td> {{ $sale->order->order_number }} </td>
                            <td> {{ $sale->customer->name}} </td>
                            <td> {{ $sale->invoice->invoice_number}} </td>
                            <td> {{number_format(( $sale->total_amount),2,'.',',')}}</td>
                            <td> {{ $sale->updated_at}} </td>
                            <td>
                                <button type="button" class="btn btn-outline-success btn-md" data-toggle="modal"
                                data-target="#modal-approve{{ $sale->id }}"><i class="fas fas fa-check-circle">
                                </i> Approve</button>
                                <button type="button" class="btn btn-outline-danger btn-md" data-toggle="modal"
                                data-target="#modal-reject{{ $sale->id }}"><i class="fas fas fa-close">
                                </i> Reject</button>
                            </td>

                        </tr>
                        <!-- approval modal-->
                        <div class="modal fade" id="modal-approve{{$sale->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h4 class="modal-title">Sales Approval fro Invoice Number {{$sale->invoice->invoice_number}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to Aprove sales for the following details?</p>
                                            Customer Name:  <b> {{$sale->customer->name}}</b><br>
                                            Invoice Number: <b> {{$sale->invoice->invoice_number}}</b><br><br>
                                        <p class="text-danger">Product from this sales order will be deducted from the stock...!</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{route('approveSales',$sale->id)}}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-success">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                          <!-- reject modal-->
                          <div class="modal fade" id="modal-reject{{$sale->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{route('rejectSales',$sale->id)}}" method="post">
                                        @csrf
                                        @method('PATCH')
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Sales reject for Invoice Number {{$sale->invoice->invoice_number}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                       <b> <p>Are you sure you want to Reject sales for the following details?</p></b>
                                            Customer Name:  <b> {{$sale->customer->name}}</b><br>
                                            Invoice Number: <b> {{$sale->invoice->invoice_number}}</b><br>
                                            Sales Amount: <b> {{number_format($sale->total_amount,2,'.',',')}}</b><br><br>

                                            <div class="form-group">
                                                <label>Comment </label>
                                                <input type="text" name="comment" class="form-control" value="" placeholder="Please enter a comment" >
                                            </div>
                                        <p class="text-danger">This sales record will be returned to the seller for correction...!</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>

                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

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
            setTimeout(function() {
                $("#success_element").hide();
            }, 2000);
            setTimeout(function() {
                $("#failure_element").hide();
            }, 2000);
        });

    </script>
@endsection
