@extends('layouts.master')

@section('content')

    <br>

    <section class="content">

            <br>


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Managing Users </h3>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addUser" style="float:right;">
                        <i class="fas fa-plus"> Add User</i>
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
                        <div class="alert alert-danger" >
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
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach( $users as $key => $user )

                            <tr>
                                <td> {{ ++$key }} </td>
                                <td> {{ $user -> first_name }} </td>
                                <td> {{ $user -> last_name }} </td>
                                <td> {{ $user -> email }} </td>
                                <td> {{ $user -> role -> name }} </td>

                                    @if($user -> status == 0)
                                    <td style="text-align: center;"> <i class="badge badge-danger">Not Active</i>
                                        <form action="{{ route('userActivate', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-success btn-sm">Activate </button>
                                        </form>
                                    </td>
                                    @else
                                    <td style="text-align: center;"> <i class="badge badge-success">Active</i>
                                        <form action="{{ route('deactivate', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Deactivate </button>
                                        </form>
                                    </td>
                                    @endif
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal-edit{{$user->id}}"><i class="fas fa-edit fa-xs" ></i> </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#modal-delete{{$user->id}}"><i class="fas fa-trash-alt">  </i></button>

                                    <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#modal-reset{{$user->id}}"><i class="fas fa-unlock" aria-hidden="true"></i> Reset</button>

                                </td>
                            </tr>
                            <!-- delete modal -->
                            <div class="modal fade" id="modal-delete{{$user->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h4 class="modal-title">Deleting {{$user->first_name}} {{$user->last_name}} </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete <b> {{$user->first_name}} {{$user->last_name}} </b> permanently? </p>
                                            You won't be able to revert this...!
                                        </div>
                                        <div class="modal-footer justify-content-between">

                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Yes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Editing User modal -->
                            <div class="modal fade" id="modal-edit{{$user->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form role="form" method="post" action="{{route('users.update',$user->id)}}" id="activityForm">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header bg-primary">
                                                <h4 class="modal-title">Editing User </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" placeholder="" >
                                                </div>
                                              {{--   <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <input type="text" name="second_name" class="form-control" value="{{ $user->second_name }}" placeholder="" >
                                                </div> --}}
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" placeholder="" >
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" placeholder="" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="role_id">Role <code>*</code></label>
                                                    <select class="form-control select2" name="role_id" id="role_id">
                                                        <option value="{{$user->role->id}}" selected> {{$user->role->name}} </option>
                                                        @foreach($roles as $role)
                                                            <option value="{{$role->id}}"> {{$role->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between ">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="Submit" class="btn btn-outline-primary"> Update! User</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                              <!-- Editing User modal -->
                              <div class="modal fade" id="modal-reset{{$user->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form role="form" method="post" action="{{route('resetPassword',$user->id)}}" id="activityForm">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header bg-primary">
                                                <h4 class="modal-title">Reset Password </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>New Password</label>
                                                    <input type="text" name="password" class="form-control" value="" placeholder="Enter Password" >
                                                </div>

                                            </div>
                                            <div class="modal-footer justify-content-between ">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="Submit" class="btn btn-outline-primary"> Reset!</button>
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

            <!-- Adding User modal -->
            <div class="modal fade" id="addUser">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form role="form" method="post" action="{{ route('users.store') }}" id="activityForm">
                            @csrf
                            @method('POST')
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title">User Addition </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" >
                                </div>
                               {{--  <div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" name="second_name" class="form-control" placeholder="Middle Name" >
                                </div> --}}
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" >
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="email" >
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select User Role <code>*</code></label>
                                    <div class="select2-purple">
                                        <select class="select2" name="role_id"  style="width: 100%;">
                                            <option selected>Select a user role...</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between ">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="Submit" class="btn btn-outline-primary"> Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </><!--/. container-fluid -->


    </section>

@endsection

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

        $(function () {
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
            setTimeout(function(){$("#success_element").hide();}, 2000);
        });
        $(document).ready(function () {
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
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

        $(document).ready(function () {
            $.validator.setDefaults({
                // submitHandler: function () {
                // alert( "Form successful submitted!" );
                // }
            });

        });
    </script>


@endsection

