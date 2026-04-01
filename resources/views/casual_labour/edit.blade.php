@extends('layouts.master')

@section('content')
    <div class="container-fluid">

            <div class="card card-default form-shell">
                <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cart-plus"></i> Editing Casual labour Order {{$order->order_number}}.</h3>
                            <small class="float-right">
                                <a href="{{route('casual_control_back_button')}}">
                                     <button type="button" class="btn btn-success btn-sm form-back-btn"><i class="fas fa-arrow-left " style="color:white;"></i> Back</button>
                                </a>
                            </small>
                </div>
                <div class="card-body form-shell__body">
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

                    <form role="form" method="post" action="{{route('casual_labour.update',$order->id)}}" id="activityForm" class="edit-form">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-11" style="margin-top: -6px;">
                                <div class="form-group">
                                    <label class="col-form-label" for="inputSuccess">Customer Name  <code>*</code></label>
                                    <select class="select2" name="customer_id"  style="width: 100%;">
                                      {{--   @foreach($order->customers as $customer) --}}
                                        <option value="{{ $order->customer->id }}"  selected> {{ $order->customer->name}}</option>
                                     {{-- @endforeach --}}
                                   </select>
                                </div>
                            </div>
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label>Customer PO Number</label>
                                    <input type="text" name="po_number" class="form-control"
                                        value="{{ old('po_number', $order->po_number) }}" placeholder="Enter customer PO number">
                                </div>
                            </div>
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label>Date <code>*</code></label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ old('date', $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                           @foreach ($order->casual_labour as $casual_labour )
                                <div class="col-md-12" id="prodContainer">
                                    <div class="row defaultRow line-item-card" >
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" name="description[]" class="form-control" value="{{$casual_labour->description}}" placeholder="Description" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Administration Fee</label>
                                                <input type="number" name="administration_fee[]" class="form-control" value="{{(int)$casual_labour->administration_fee}}" placeholder="Description" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Labour Charges</label>
                                                <input type="number" name="labour_charges[]" class="form-control" value="{{(int)$casual_labour->labour_charge}}" placeholder="Item Quantity" >
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity[]" class="form-control" value="{{(int)$casual_labour->quantity}}" placeholder="Unit Price" >
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-lg-1">
                                            <div class="line-item-actions">
                                            <button type="button" class="btn btn-primary line-item-btn line-item-btn--add" onclick="addProduct()"> <i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           @endforeach

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="hidden" name="apply_discount" value="0">
                                                <input class="form-check-input" type="checkbox" name="apply_discount" id="applyDiscount" value="1" {{-- {{ $order->invoice->discount !== 0 ? 'checked' : '' }} --}}>
                                                <label class="form-check-label" for="applyDiscount">
                                                    Apply Discount
                                                </label>
                                            </div>
                                            <div id="discountInput" style="display:none;">
                                                <input type="number" name="discount_amount" class="form-control"  placeholder="Discount Amount">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="hidden" name="withholding" value="0">
                                                <input class="form-check-input" type="checkbox" name="withholding" id="withholding" value="1"  {{-- {{ $order->invoice->vat !== 0.00 ? 'checked' : '' }} --}}>
                                                <label class="form-check-label" for="withholding">
                                                   Withholding Tax
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="hidden" name="apply_vat" value="0">
                                        <input class="form-check-input" type="checkbox" name="apply_vat" id="applyVAT" value="1"  {{-- {{ $order->invoice->vat !== 0.00 ? 'checked' : '' }} --}}>
                                        <label class="form-check-label" for="applyVAT">
                                            Apply VAT
                                        </label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="form-submit-row">
                            <button type="Submit" class="btn btn-outline-primary form-submit-btn"> Update Order</button>
                        </div>
                    </form>
                </div>
            </div>

    </div>



@endsection

@section('pagescripts')
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

<script type="text/javascript">

$(function() {

    $('.select2').select2()
                setTimeout(function() {
                    $("#success_element").hide();
        },
    2000);
});


function addProduct() {
    event.preventDefault();
    const productsContainer = $('#prodContainer');
    const newProductDiv = $(document.createElement('div'));

    newProductDiv.html(`
        <!-- Your HTML code for a new product -->
        <div class="newly row line-item-card">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description[]" class="form-control" placeholder="Description" >
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Administration Fee</label>
                    <input type="number" name="administration_fee[]" class="form-control" placeholder="0.0" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Labour Charges</label>
                    <input type="number" name="labour_charges[]" class="form-control" placeholder="0.0" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" placeholder="0" >
                </div>
            </div>
            <div class="col-md-2 col-lg-1">
                <div class="line-item-actions">
                <button class="btn btn-primary line-item-btn line-item-btn--add" onclick="addProduct()"> <i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="col-md-2 col-lg-1">
                <div class="line-item-actions">
                <button type="button" class="btn btn-danger line-item-btn line-item-btn--remove" onclick="removeProduct(this)"> <i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
    `);

    // Insert newProductDiv after the last child of productsContainer
   // productsContainer.children().last().after(newProductDiv);
   newProductDiv.appendTo(productsContainer);

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
 document.getElementById('applyDiscount').addEventListener('change', function() {
 document.getElementById('discountInput').style.display = this.checked ? 'block' : 'none';
});

</script>
@endsection
