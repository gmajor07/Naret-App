
@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-sort-amount-down"></i> &nbsp Stock Below Minimum Required Quantity </h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addStock"
            style="float:right;">
            <i class="fas fa-plus"> Add Product Stock</i>
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Remaining Stock Quantity</th>
                        <th>Minimum Required Quantity</th>
                        {{-- <th width="10%">Action</th> --}}
                    </tr>
                </thead>
                <tbody>

                    @foreach ($product_below_stock as $key => $product)
                        <tr>
                            <td> {{ ++$key }} </td>
                            <td> {{ $product->name }} </td>
                            <td> {{ $product->description}} </td>
                            <td> {{ $product->stock_quantity}} </td>
                            <td> 50 </td>
                          {{--   <td style="text-align: center;">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-edit{{ $product->id }}"><i class="fas fa-edit fa-xs"></i> </button>

                            </td> --}}
                        </tr>



                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
     <!-- Adding product modal -->
     <div class="modal fade" id="addStock">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('stocks.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Add Stock</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select User Role <code>*</code></label>
                                    <div class="select2-purple">
                                        <select class="select2" name="product_id"  style="width: 100%;">
                                            <option selected>Select a product...</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: 5px;">
                                    <label>Quantity</label>
                                    <input type="text" name="quantity" class="form-control" placeholder="Quantity" >
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Add Stock</button>
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
            $('#receivingForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    purchased_date: {
                        required: true,
                    },
                    condition: {
                        required: true,
                    },
                    serial_number: {
                        required: true,
                    },
                    product_number: {
                        required: true,
                    },
                    location: {
                        required: true,
                    },
                    activity: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter asset name",

                    },
                    purchased_date: {
                        required: "Please enter date bought",
                    },
                    condition: {
                        required: "Please enter condition",
                    },
                    serial_number: {
                        required: "Please enter serial number",
                    },
                    product_number: {
                        required: "Please enter production number",
                    },
                    location: {
                        required: "Please select a condition",
                    },
                    activity: {
                        required: "Please select a activity",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
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
