
@extends('layouts.master')

@section('content')
    <br>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cart-plus "></i> Product Orders</h3>
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addOrder"
                style="float:right;">
                <i class="fas fa-plus"> Create Order</i>
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
                        <th>Order Number</th>
                        <th>Customer Name</th>
                        <th>Customer Address</th>
                        <th>Customer Email</th>
                        <th>Customer Phone</th>
                        <th>Description</th>
                        <th>Invoice Number</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        {{-- <th>Account for</th> --}}
                        <th width="10%"></th>
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
                            <td> {{ $order->customer->phone}} </td>
                            <td> {{ $order->description ?? 'N/A' }} </td>
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

                            <td> {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y')}} </td>
                            {{-- <td> {{ $account->account_for}} </td> --}}
                            <td style="text-align: center;">
                                <a href="{{route('orders.show',$order->id)}}">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye fa-xs"></i> </button>
                                </a>

                                @if ($order->status == 0)
                                    <a href="{{route('orders.edit',$order->id)}}">
                                        <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit fa-xs"></i> </button>
                                    </a>

                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                        data-target="#modal-delete{{ $order->id }}">
                                        <i class="fas fa-trash-alt"></i></button>
                                @endif

                            </td>
                        </tr>

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
                                        <p>Are you sure you want to delete <b> {{$order->order_number}}  </b> permanently? </p>
                                        You won't be able to revert this...!
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                        <form action="{{route('orders.destroy',$order->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
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

    {{-- adding product --}}
    <!-- Adding product modal -->
    <div class="modal fade" id="addOrder">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('orders.store')}}" id="activityForm">
                    @csrf
                    @method('POST')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Create Order</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="prodContainer" >

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Select Customer  <code>*</code></label>
                                    <select class="select2" name="customer_id"  style="width: 100%;">
                                        <option selected>Select Customer...</option>
                                        @foreach($customers as $customer)
                                           <option value="{{ $customer->id }}">{{ $customer->name}}</option>
                                        @endforeach
                                   </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Customer PO Number</label>
                                    <input type="text" name="po_number" class="form-control"
                                        placeholder="Enter customer PO number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Enter order description"></textarea>
                                </div>
                            </div>
                         <div class="col-md-12" id="prodContainer">
                           <div class="row" >
                            <div class="col-md-5">
                                <div class="form-group" >
                                    <label class="col-form-label" for="inputSuccess">Select Product  </label>
                                    <select class="select2" name="product_ids[]"  style="width: 100%;">
                                        <option selected>Select Product...</option>
                                        @foreach($products as $product)
                                           <option value="{{ $product->id }}">{{ $product->name}}</option>
                                        @endforeach
                                   </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group" style="margin-top:5px;">
                                    <label>Quantity</label>
                                    <input type="number" name="quantities[]" class="form-control" placeholder="Quantity" >
                                </div>
                            </div>

                              <div class="col-md-1">
                                <button type="button" class="btn btn-primary" style="width:30px; height:30px;align:center;font-size:8px;margin-top:40px;" onclick="addProduct()"> <i class="fas fa-plus" style="margin-left:-2px;;"></i></button>
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
                                            <input class="form-check-input" type="checkbox" name="withholding2" id="withholding2" value="1">
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
                        <button type="Submit" class="btn btn-outline-primary"> Create Order</button>
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
    // Use $(document).ready() to ensure the DOM is fully loaded
  // Define addProduct in the global scope
    function addProduct() {
        event.preventDefault();
                const productsContainer = $('#prodContainer');
                const newProductDiv = $(document.createElement('div'));

                newProductDiv.html(`
                    <!-- Your HTML code for a new product -->
                    <div class="newly row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-form-label" for="inputSuccess">Select Product</label>
                                <select class="select2" name="product_ids[]" style="width: 100%;">
                                    <option selected>Select Product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group" style="margin-top:5px;">
                                <label>Quantity</label>
                                <input type="number" name="quantities[]" class="form-control" placeholder="Quantity">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" style="width:30px; height:30px;align:center;font-size:8px;margin-top:40px;" onclick="addProduct()"> <i class="fas fa-plus" style="margin-left:-2px;"></i></button>
                        </div>
                    <div class="col-md-1">
                                    <!-- Add a button to remove this product -->
                                    <button type="button" class="btn btn-danger" style="width:30px; height:30px;align:center;font-size:8px;margin-top:40px;"onclick="removeProduct(this)"> <i class="fas fa-minus" style="margin-left:-2px;;"></i></button>
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

            document.getElementById('applyDiscount2').addEventListener('change', function() {
            document.getElementById('discountInput2').style.display = this.checked ? 'block' : 'none';
            });

</script>
@endsection
