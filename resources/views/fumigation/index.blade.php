@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cart-plus"></i> Fumigation Service </h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addAccount"
                style="float:right;">
                <i class="fas fa-plus"> Add Fumigation Order</i>
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
                        <th>Fumigation Order Number</th>
                        <th>Customer</th>
                        <th>Customer Addres</th>
                        <th>Customer Email</th>
                        <th>Customer Phone</th>
                        <th>Invoice Number</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        {{-- <th>Account for</th> --}}
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>



                    @foreach ($orders as $key => $order)
                        <tr>
                            <td> {{ ++$key }} </td>
                            <td> {{ $order->order_number }} </td>
                            <td> {{ $order->customer->name}} </td>
                            <td> {{ $order->customer->location}} </td>
                            <td> {{ $order->customer->email}} </td>
                            <td> {{ $order->customer->phone }} </td>
                            <td> {{ $order->invoice->invoice_number}} </td>
                            @if ($order->status == 0)
                            <td class="text-warning">Pending</td>
                            @elseif ($order->status == 1)
                            <td class="text-info">Partial Paid</td>
                            @elseif ($order->status == 2)
                            <td class="text-success">Paid</td>
                            @else
                            <td class="text-danger">Cancelled</td>
                            @endif


                            <td> {{ $order->order_date}} </td>
                            {{-- <td> {{ $account->account_for}} </td> --}}
                            <td style="text-align: auto;">
                                <a href="{{route('fumigation.show', $order->id)}}">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                      <i class="fas fa-eye fa-xs"></i> </button>
                                    </a>
                                @if ($order->status !=2)
                                <a href="{{route('fumigation.edit', $order->id)}}">
                                    <button type="button" class="btn btn-outline-success btn-sm" >
                                        <i class="fas fa-edit fa-xs"></i> </button>
                                    </a>

                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                     data-target="#modal-cancel{{ $order->id }}">
                                    <i class="fas fa-close">
                                    </i></button>

                                @endif
                              {{--       <div class="btn-group">
                                        <button type="button" class="btn btn-default">Action</button>
                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                          <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                          <a class="dropdown-item" href="{{route('fumigation.show', $order->id)}}">View</a>
                                          <a class="dropdown-item" href="{{route('fumigation.edit', $order->id)}}">Edit</a>
                                          <a class="dropdown-item" href="#">Assign</a>
                                          <a class="dropdown-item" href="#">Cancel</a>
                                    </div> --}}

                            </td>
                        </tr>
                       <!-- cancel modal -->
                        <div class="modal fade" id="modal-cancel{{$order->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Canceling  Order {{$order->order_number}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to Cancel order <b>{{$order->order_number}}</b> for<b> {{$order->customer->name}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{ route('fumigationCancell', $order->id) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                          <!-- delete modal -->
                          <div class="modal fade" id="modal-delete{{$order->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title">Deleting {{$order->order_number}} </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to Delete order <b>{{$order->order_number}}</b> for<b> {{$order->customer->name}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{ route('fumigation.destroy', $order->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editing fumigation modal -->
                        <div class="modal fade" id="modal-edit{{$order->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form role="form" method="post" action="{{route('fumigationCancell',$order->id)}}" id="activityForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">Editing Fumigation Order {{ $order->order_number }} </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input type="text" name="description" value="{{-- {{ $fumigation->description }} --}}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Item Quantity</label>
                                                        <input type="number" name="item_quantity" value="{{-- {{ $fumigation->item_quantity }} --}}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Unit Price</label>
                                                        <input type="number" name="unit_price" value="{{-- {{ $fumigation->unit_price }} --}}" class="form-control" placeholder="" >
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary"> Update Fumigation Order</button>
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


    <!-- Adding fumigation order modal -->
    <div class="modal fade" id="addAccount">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('fumigation.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Create Fumigation Order</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-11" style="margin-top: -6px;">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Customer  <code>*</code></label>
                                    <select class="select2" name="customer_id"  style="width: 100%;">
                                        <option value="" disabled selected>Customers...</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"> {{ $customer->name}}</option>
                                     @endforeach
                                   </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Customer PO Number</label>
                                    <input type="text" name="po_number" class="form-control" placeholder="Enter customer PO number">
                                </div>
                            </div>

                            <div class="col-md-12" id="prodContainer">
                                    <div class="row" >
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" name="description[]" class="form-control" placeholder="Description" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Item Quantity</label>
                                                <input type="number" name="item_quantity[]" class="form-control" placeholder="Item Quantity" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Unit Price</label>
                                                <input type="number" name="unit_price[]" class="form-control" placeholder="Unit Price" >
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary" style="width:30px; height:30px;align:center;font-size:8px;margin-top:37px;" onclick="addProduct()"> <i class="fas fa-plus" style="margin-left:-2px;;"></i></button>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="apply_discount" id="applyDiscount">
                                            <label class="form-check-label" for="applyDiscount">
                                                Apply Discount
                                            </label>
                                        </div>
                                        <div id="discountInput" style="display:none;">
                                            <input type="number" name="discount_amount" class="form-control" placeholder="Discount Amount">
                                        </div>
                                    </div>
                               </div>

                               <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="withholding" id="withholding" value="1">
                                        <label class="form-check-label" for="withholding">
                                            Withholding Tax
                                        </label>
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="apply_vat" id="applyVAT" value="1">
                                            <label class="form-check-label" for="applyVAT">
                                                Apply VAT
                                            </label>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" name="date" class="form-control" placeholder="dd-mm-yyyy" id="date" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" ><br><br>
                                        <label class="col-form-label" for="inputSuccess">Select Currency  </label>
                                        <select class="select2" name="currency_id"  style="width: 50%;">
                                            @foreach($currencies as $currency)
                                               <option value="{{ $currency->id }}" @if($loop->first) selected @endif>{{ $currency->name}}</option>
                                            @endforeach
                                       </select>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-outline-primary"> Create Fumigation Order</button>
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

    <!-- SweetAlert2 -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

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

                  /*   var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    $('.swalDefaultSuccess').click(function() {
                        Toast.fire({
                            icon: 'success',
                            title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
                        })
                    }); */


            });


        function addProduct() {
            event.preventDefault();
                    const productsContainer = $('#prodContainer');
                    const newProductDiv = $(document.createElement('div'));

                    newProductDiv.html(`
                        <!-- Your HTML code for a new product -->
                        <div class="newly row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="description[]" class="form-control" placeholder="Description" >
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Item Quantity</label>
                                        <input type="number" name="item_quantity[]" class="form-control" placeholder="Item Quantity" >
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Unit Price</label>
                                        <input type="number" name="unit_price[]" class="form-control" placeholder="Unit Price" >
                                    </div>
                                </div>

                            <div class="col-md-1">
                                <button class="btn btn-primary" style="width:30px; height:30px;align:center;font-size:8px;margin-top:40px;" onclick="addProduct()"> <i class="fas fa-plus" style="margin-left:-2px;"></i></button>
                            </div>
                        <div class="col-md-1">
                                        <!-- Add a button to remove this product -->
                                        <button type="button" class="btn btn-danger" style="width:30px; height:30px;align:center;font-size:8px;margin-top:40px;margin-left:-20px;"
                                        onclick="removeProduct(this)"> <i class="fas fa-minus" style="margin-left:-2px;;"></i></button>
                                    {{--  <button class="btn btn-danger" onclick="removeProduct(this)">Remove</button> --}}
                        </div>
                    </div>
                    `);

                    productsContainer.append(newProductDiv);

                    // Reinitialize Select2 for the new select element
                    $('.select2').select2()
                    setTimeout(function() {
                        $("#success_element").hide();
                    }, 2000);
                }
                function removeProduct(button) {
                        // Find the parent product div and remove it
                        $(button).closest('.newly').remove();
                    }


                // Use $(document).ready() to ensure the DOM is fully loaded
                $(document).ready(function() {
                    // Your other initialization code here
                });
                document.getElementById('applyDiscount').addEventListener('change', function() {
                document.getElementById('discountInput').style.display = this.checked ? 'block' : 'none';
        });

    </script>

{{-- <script>
    console.log("Script is running");

// Check if there's a success message in the session
let successMessage = '{{ Session::get('success') }}';

// If there's a success message, show it using SweetAlert2 Toast
if (successMessage) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'success',
        title: successMessage
    });
}
</script> --}}


@endsection
