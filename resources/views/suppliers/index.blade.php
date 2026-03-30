@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Suppliers </h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addSupplier"
                style="float:right;">
                <i class="fas fa-plus"> Add Supplier</i>
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
                        <th>Tin Number</th>
                        <th>VRN</th>
                        <th>Address</th>
                        <th>email</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($suppliers as $key => $supplier)
                        <tr>
                            <td> {{ ++$key }} </td>
                            <td> {{ $supplier->name }} </td>
                            <td> {{ $supplier->tin_number }} </td>
                            <td> {{ $supplier->vrn }} </td>
                            <td> {{ $supplier->address }} </td>
                            <td> {{ $supplier->email }} </td>
                            <td> {{ $supplier->phone }} </td>
                            @if ($supplier->status == 1)
                                <td style="text-align: center;"> <i class="badge badge-success">Active</i>

                                </td>
                            @else
                                <td style="text-align: center;"> <i class="badge badge-danger">Not Active</i>
                                </td>
                            @endif
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-edit{{ $supplier->id }}"><i class="fas fa-edit fa-xs"></i> </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                    data-target="#modal-delete{{ $supplier->id }}"><i class="fas fa-trash-alt">
                                    </i></button>
                            </td>
                        </tr>
                       <!-- delete modal -->
                        <div class="modal fade" id="modal-delete{{$supplier->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Deleting {{$supplier->name}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <b> {{$supplier->name}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editing User modal -->
                        <div class="modal fade" id="modal-edit{{$supplier->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form role="form" method="post" action="{{route('suppliers.update',$supplier->id)}}" id="activityForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">Editing Supplier {{ $supplier->name }} </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tin Number</label>
                                                        <input type="text" name="tin" class="form-control" value="{{ $supplier->tin_number }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Vrn</label>
                                                        <input type="text" name="vrn" class="form-control" value="{{ $supplier->vrn }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" name="email" class="form-control" value="{{ $supplier->email }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input type="text" name="address" class="form-control" value="{{ $supplier->address }}" placeholder="" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="role_id">Satus <code>*</code></label>
                                                <select class="form-control select2" name="status" id="status">
                                                    @if($supplier->status==1)
                                                    <option value="{{$supplier->status}}" selected>Active</option>
                                                    @elseif ($supplier->status==0)
                                                    <option value="{{$supplier->status}}" selected>Not Active</option>
                                                    @endif
                                                    @if($supplier->status==1)
                                                    <option value="0"> Not Active </option>
                                                    @endif
                                                    @if($supplier->status==0)
                                                    <option value="1">Active </option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary"> Update! Supplier</button>
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

    {{-- adding customer --}}
    <!-- Adding User modal -->
    <div class="modal fade" id="addSupplier">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('suppliers.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Supplier Addition </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Full Name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tin Number</label>
                                    <input type="text" name="tin" class="form-control" placeholder="Tin Number" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vrn</label>
                                    <input type="text" name="vrn" class="form-control" placeholder="Vrn" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Phone" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>address</label>
                                    <input type="text" name="address" class="form-control" placeholder="Address" >
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Add Supplier</button>
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
