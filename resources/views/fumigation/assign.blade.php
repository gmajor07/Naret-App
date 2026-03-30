@extends('layouts.master')

@section('content')
<br>
    <div class="container-fluid">

            <div class="card card-default ">
                <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cart-plus"></i>   Assign Fumigants to Fumigator.</h3>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addAccount"
                            style="float:right;">
                            <i class="fas fa-plus"> Assign to Fumigator</i>
                        </button>
                </div>


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


                    <br>
                    @php
                    $i=0;
                    @endphp

                    <!-- First Table row -->
                    <div class="row">
                      <div class="col-12 table" >
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>

                              <th>S/N</th>
                              <th>Fumigant</th>
                              <th>Item Quantity</th>
                              <th>Status</th>
                              <th>Action</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($consumptions as $key => $consumption)
                                  <tr>
                                    <td>{{ ++$key}}</td>
                                    <td><a href="#">  {{$consumption->product->name}} </a></td>
                                    <td> {{$consumption->item_quantity}} </td>
                                    <td>
                                      @if ($consumption->status == 0)
                                          <b style="color: green">In use</b>
                                       @else
                                          <b style="color: red">Finished</b>
                                      @endif
                                    </td>
                                    <td>
                                        <a href="{{route('assignedConsumptionShow', $consumption->id)}}">
                                            <button type="button" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye fa-xs"></i> </button>
                                        </a>
                                        @if (!$consumption->status == 1)
                                        <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal"
                                        data-target="#modal-edit{{ $consumption->id }}"><i class="fas fa-plus fa-xs"></i> </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                        data-target="#modal-delete{{ $consumption->id }}"><i class="fas fa-exclamation-triangle fa-xs"></i> </button>
                                        @endif
                                    </td>

                                  </tr>

                                    <!-- delete modal -->
                        <div class="modal fade" id="modal-delete{{$consumption->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Record Finished Fumigant</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to mark <b>{{$consumption->item_quantity}} bottles</b> of  <b>{{$consumption->product->name}}</b> fumigant given to <b> {{$consumption->user()->first()->first_name}} {{$consumption->user()->first()->last_name}} as </b> Finished? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{route('addFumigationAsFinished', $consumption->id)}}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                                  <!-- Editing fumigation modal -->
                        <div class="modal fade" id="modal-edit{{$consumption->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form role="form" method="post" action="{{route('assignUpdate',$consumption->id)}}" id="activityForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">Add Product Usage</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-11" style="margin-top: -6px;">
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="inputSuccess">Select Fumigator  <code>*</code></label>
                                                        <select class="select2" name="user_id"  style="width: 100%;">
                                                            <option value="" disabled selected>Fumigators...</option>
                                                            @foreach($users as $user)
                                                            <option value="{{ $user->id }}" {{ $user->id == $consumption->user()->first()->id ? 'selected' : '' }}> {{ $user->first_name}} {{ $user->last_name}}</option>
                                                         @endforeach
                                                       </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Container Quantity</label>
                                                        <input type="number" name="container_quantity" class="form-control" placeholder="Container Quantity" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6" >
                                                    <div class="form-group" style="margin-top:-5px;">
                                                        <label class="col-form-label" for="inputSuccess">Select Fumigation Used  <code>*</code></label>
                                                        <select class="select2" name="fumigation_id"  style="width: 100%;">
                                                            <option value="" disabled selected>Fumigations...</option>
                                                            @foreach($fumigations as $fumigation)
                                                            <option value="{{ $fumigation->id }}"> {{ $fumigation->description}}</option>
                                                         @endforeach
                                                       </select>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary"> Add Product Usage</button>
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

                </div>
            </div>

    </div>

       <!-- Assign to fumigator modal -->
       <div class="modal fade" id="addAccount">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('assignStore')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Assign to Responsible Fumigator</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-11" style="margin-top: -6px;">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Fumigator  <code>*</code></label>
                                    <select class="select2" name="user_id"  style="width: 100%;">
                                        <option value="" disabled selected>Fumigators...</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}"> {{ $user->first_name}} {{ $user->last_name}}</option>
                                     @endforeach
                                   </select>
                                </div>
                            </div>

                            <div class="col-md-12" id="prodContainer">
                                    <div class="row" >
                                        <div class="col-md-6" style="margin-top: -6px;">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Select Fumigant  <code>*</code></label>
                                                <select class="select2" name="product_id"  style="width: 100%;">
                                                    <option value="" disabled selected>Fumigant...</option>
                                                    @foreach($products as $product)
                                                    <option value="{{ $product->id }}"> {{ $product->name}}</option>
                                                 @endforeach
                                               </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Item Quantity</label>
                                                <input type="number" name="item_quantity" class="form-control" placeholder="Item Quantity" >
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Container Quantity</label>
                                                <input type="number" name="container_quantity" class="form-control" placeholder="Container Quantity" >
                                            </div>
                                        </div>

                                        <div class="col-md-6" >
                                            <div class="form-group" style="margin-top:-5px;">
                                                <label class="col-form-label" for="inputSuccess">Select Fumigation Used  <code>*</code></label>
                                                <select class="select2" name="fumigation_id"  style="width: 100%;">
                                                    <option value="" disabled selected>Fumigations...</option>
                                                    @foreach($fumigations as $fumigation)
                                                    <option value="{{ $fumigation->id }}"> {{ $fumigation->description}}</option>
                                                 @endforeach
                                               </select>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Assign to Fumigator</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('pagescripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
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
