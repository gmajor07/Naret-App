
@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Manage Transactions</h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addTransaction"
                style="float:right;">
                <i class="fas fa-plus"> Add Transaction</i>
            </button>
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
                        <th>S/N</th>
                        <th>Description</th>
                        <th>DR</th>
                        <th>CR</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($transactions as $key => $transaction)
                        <tr>
                            <td> {{ ++$key }} </td>
                            <td> {{ $transaction->description }} </td>
                            <td> {{ $transaction->dr}}</td>
                            <td> {{ $transaction->cr}}</td>
                            <td> {{number_format(( $transaction->amount),2,'.',',')}}</td>
                            <td>{{\Carbon\Carbon::parse($transaction->date)->format('d-m-Y')}}</td>

                            <td style="text-align: center;">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-edit{{ $transaction->id }}"><i class="fas fa-edit fa-xs"></i> </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                    data-target="#modal-delete{{ $transaction->id }}"><i class="fas fa-trash-alt">
                                    </i></button>
                            </td>
                        </tr>
                       <!-- delete modal -->
                        <div class="modal fade" id="modal-delete{{$transaction->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Deleting Transaction </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete transaction<b> {{$transaction->description}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editing Transaction modal -->
                        <div class="modal fade" id="modal-edit{{$transaction->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form role="form" method="post" action="{{route('transactions.update',$transaction->id)}}" id="activityForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">Editing Transaction{{--  {{ $transaction->description }} --}} </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input type="text" name="description" class="form-control" value="{{ $transaction->description}}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="text" name="amount" class="form-control" value="{{ $transaction->amount }}" placeholder="" >
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>DR</label>
                                                        <input type="text" name="dr" class="form-control" placeholder="" id="dr" value="{{$transaction->dr}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>CR</label>
                                                        <input type="text" name="cr" class="form-control" placeholder="" id="cr" value="{{$transaction->cr}}">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="date" name="date" class="form-control" value="{{$transaction->date}}" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary"> Update Transaction</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


    <!-- Adding transaction modal -->
    <div class="modal fade" id="addTransaction">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('transactions.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Add Transaction</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Description" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" name="amount" class="form-control" placeholder="Amount" >
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DR</label>
                                    <input type="text" name="dr" class="form-control" placeholder="" id="dr" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CR</label>
                                    <input type="text" name="cr" class="form-control" placeholder="" id="cr" >
                                </div>
                            </div>
                        </div>



                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control" placeholder="dd-mm-yyyy" id="date" >
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Add Transaction</button>
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

            //Initialize Select2 Elements
            $('.select2').select2()
            setTimeout(function() {
                $("#success_element").hide();
            }, 2000);
        });
        $(document).ready(function() {
            $.validator.setDefaults({
                // submitHandler: function () {
                // alert( "Form successful submitted!" );
                // }
            });

        });

    </script>
@endsection
