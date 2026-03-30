@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Managing Product </h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addProduct"
                style="float:right;">
                <i class="fas fa-plus"> Add New Product</i>
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
                        <th>Stock Quantity</th>
                        <th>Unity of Measure</th>
                        <th>Product Type</th>
                        <th>Unit Price</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($products as $key => $product)
                        <tr>
                            <td> {{ ++$key }} </td>
                            <td> {{ $product->name }} </td>
                            <td> {{ $product->description}} </td>
                            <td> {{ $product->stock_quantity}} </td>
                            <td> {{ $product->unit_measure->name }} </td>
                            <td> {{ $product->type->name}} </td>
                            <td> {{number_format(( $product->unity_price),2,'.',',')}}</td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-edit{{ $product->id }}"><i class="fas fa-edit fa-xs"></i> </button>
                                 @if (Auth::user()->role_id == 1)
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                    data-target="#modal-delete{{ $product->id }}"><i class="fas fa-trash-alt">
                                    </i></button>
                                @endif
                            </td>
                        </tr>
                       <!-- delete modal -->
                        <div class="modal fade" id="modal-delete{{$product->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Deleting {{$product->name}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <b> {{$product->name}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editing User modal -->
                        <div class="modal fade" id="modal-edit{{$product->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form role="form" method="post" action="{{route('products.update',$product->id)}}" id="activityForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">Editing Product {{ $product->name }} </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Product Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input type="text" name="description" class="form-control" value="{{ $product->description }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Stock Quantity</label>
                                                        <input type="text" name="quantity" class="form-control" value="{{ $product->stock_quantity }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: -6px;">
                                                        <label class="col-form-label" for="inputSuccess">Select Unit Measure  <code>*</code></label>
                                                        <select class="select2"  name="unit_measure_id"  style="width: 100%;" >
                                                             @foreach($unit_measures as $unit_measure)
                                                                    <option value="{{ $unit_measure->id }}" {{ $product->unit_measure_id == $unit_measure->id ? 'selected' : '' }}>{{ $unit_measure->name}}</option>
                                                             @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group" style="margin-top: -6px;">
                                                        <label class="col-form-label" for="inputSuccess">Select Product type  <code>*</code></label>
                                                        <select class="select2" id="selectBox2" name="type_id"  style="width: 100%;"  onchange="showHideInput()">
                                                            @foreach($types as $type)
                                                                @if($type->id  != 3)
                                                                    <option value="{{ $type->id }}" {{ $product->type_id == $type->id ? 'selected' : '' }}>{{ $type->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- @if ($product->type_id == 1) --}}
                                                    <div class="col-md-6" id="inputBox2">
                                                        <div class="form-group">
                                                            <label>Unit Price</label>
                                                            <input type="text" name="price" class="form-control" value="{{ $product->unity_price }}" placeholder="" >
                                                        </div>
                                                    </div>
                                                {{-- @endif --}}

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary"> Update Product</button>
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

    {{-- adding product --}}
    <!-- Adding product modal -->
    <div class="modal fade" id="addProduct">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('products.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Add New Product</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Product Name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Description" >
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: -6px;">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select type  <code>*</code></label>
                                    <select class="select2" id="selectBox" name="type_id"  style="width: 100%;"  onchange="showHideInput()">
                                        <option selected>Select Type...</option>
                                         @foreach($types as $type)
                                                @if($type->id  != 3)
                                                   <option value="{{ $type->id }}">{{ $type->name}}</option>
                                                @endif
                                         @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Quantity</label>
                                    <input type="text" name="quantity" class="form-control" placeholder="Stock Quantity" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: -6px;">
                                    <label class="col-form-label" for="inputSuccess">Select Unit Measure  <code>*</code></label>
                                    <select class="select2"  name="unit_measure_id"  style="width: 100%;" >
                                        <option selected>Select Unit Measure...</option>
                                         @foreach($unit_measures as $unit_measure)
                                                <option value="{{ $unit_measure->id }}">{{ $unit_measure->name}}</option>
                                         @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" style="display: none;" id="inputBox" >
                                <div class="form-group">
                                    <label>Unit Price</label>
                                    <input type="text" name="price" class="form-control" placeholder="Unit Price" >
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Add Product</button>
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

    function showHideInput() {
        var selectBox = document.getElementById("selectBox");
        var inputBox = document.getElementById("inputBox");

        var selectBox2 = document.getElementById("selectBox2");
        var inputBox2 = document.getElementById("inputBox2");

        if (selectBox.value === "1") {
        inputBox.style.display = "block";
        } else {
        inputBox.style.display = "none";
        }

        if (selectBox2.value === "1") {
        inputBox2.style.display = "block";
        } else {
        inputBox2.style.display = "none";
        }
    }
    </script>
@endsection
