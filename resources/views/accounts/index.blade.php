@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Managing Bank Accounts </h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addAccount"
                style="float:right;">
                <i class="fas fa-plus"> Add Account</i>
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
                        <th>Swift Code</th>
                        <th>Financial Institution</th>
                        <th>Branch</th>
                        <th>Account Name</th>
                        <th>Account Number</th>
                        <th>Currency</th>
                        <th>Company</th>
                        {{-- <th>Account for</th> --}}
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>



                    @foreach ($accounts as $key => $account)
                        <tr>
                            <td> {{ ++$key }} </td>
                            <td> {{ $account->swift_code }} </td>
                            <td> {{ $account->financial_institution}} </td>
                            <td> {{ $account->branch}} </td>
                            <td> {{ $account->account_name}} </td>
                            <td> {{ $account->account_number}} </td>
                            <td> {{ $account->currency->name}} </td>
                            <td> {{ $account->company->name}} </td>
                            {{-- <td> {{ $account->account_for}} </td> --}}
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                    data-target="#modal-edit{{ $account->id }}"><i class="fas fa-edit fa-xs"></i> </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                    data-target="#modal-delete{{ $account->id }}"><i class="fas fa-trash-alt">
                                    </i></button>
                            </td>
                        </tr>
                       <!-- delete modal -->
                        <div class="modal fade" id="modal-delete{{$account->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Deleting {{$account->name}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete account <b>{{$account->account_number}}</b> for<b> {{$account->account_name}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{ route('accounts.destroy', $account->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editing acount modal -->
                        <div class="modal fade" id="modal-edit{{$account->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form role="form" method="post" action="{{route('accounts.update',$account->id)}}" id="activityForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">Editing Account {{ $account->account_number }} {{ $account->financial_institution }} </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Swift Code</label>
                                                        <input type="text" name="swift" value="{{ $account->swift_code }}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Financial Institution</label>
                                                        <input type="text" name="institution" value="{{ $account->financial_institution }}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Branch</label>
                                                        <input type="text" name="branch" value="{{ $account->branch }}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Account Name</label>
                                                        <input type="text" name="name" value="{{ $account->account_name }}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Account Number</label>
                                                        <input type="text" name="number" class="form-control" value="{{ $account->account_number }}" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: -6px;">
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="inputSuccess">Select Currency  <code>*</code></label>
                                                        <select class="select2" name="currency_id"  style="width: 100%;">
                                                            @foreach($currencies as $currency)
                                                               <option value="{{ $currency->id }}" {{ $account->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12" style="margin-top: -6px;">
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="inputSuccess">Select Company <code>*</code></label>
                                                        <select class="select2" name="company_id" style="width: 100%;">
                                                            @foreach($companies as $company)
                                                                <option value="{{ $company->id }}" {{ $account->company_id == $company->id ? 'selected' : '' }}>
                                                                    {{ $company->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary"> Update Account</button>
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
    <div class="modal fade" id="addAccount">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('accounts.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Add Account</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Swift Code</label>
                                    <input type="text" name="swift" class="form-control" placeholder="Swift Code" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Financial Institution</label>
                                    <input type="text" name="institution" class="form-control" placeholder="Financial Institution" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <input type="text" name="branch" class="form-control" placeholder="Branch" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Account name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" name="number" class="form-control" placeholder="Account Number" >
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: -6px;">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Currency  <code>*</code></label>
                                    <select class="select2" name="currency_id"  style="width: 100%;">
                                        @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" @if($loop->first) selected @endif>{{ $currency->name}}</option>
                                     @endforeach
                                   </select>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: -6px;">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Company  <code>*</code></label>
                                    <select class="select2" name="company_id"  style="width: 100%;">

                                        @foreach($companies as $company)
                                           <option value="{{ $company->id }}" @if($loop->first) selected @endif>{{ $company->name}}</option>
                                        @endforeach
                                   </select>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Add Account</button>
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
