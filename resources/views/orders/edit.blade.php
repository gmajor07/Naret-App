@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card card-default form-shell">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cart-plus"></i> Editing Order {{$order->order_number}}</h3>
            <small class="float-right">
                <a href="{{ route('order_control_back_button') }}">
                    <button type="button" class="btn btn-success btn-sm form-back-btn">
                        <i class="fas fa-arrow-left " style="color:white;"></i> Back
                    </button>
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

            <form method="post" action="{{ route('orders.update', $order->id) }}" class="edit-form">
                @csrf
                @method('PATCH')

                <!-- Customer Selection -->
                <div class="form-group">
                    <label>Customer Name <code>*</code></label>
                    <select class="select2" name="customer_id" style="width: 100%;">
                        <option value="{{ $order->customer->id }}" selected>{{ $order->customer->name }}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>Customer PO Number</label>
                    <input type="text" name="po_number" class="form-control"
                        value="{{ old('po_number', $order->po_number) }}" placeholder="Enter customer PO number">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Enter order description">{{ $order->description }}</textarea>
                </div>

                <div class="form-group">
                    <label>Date <code>*</code></label>
                    <input type="date" name="date" class="form-control"
                        value="{{ old('date', $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '') }}">
                </div>

                <!-- Products & Quantities -->
                <div id="prodContainer">
                    @foreach ($order->products as $orderProduct)
                        <div class="row product-row line-item-card">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product</label>
                                    <select class="select2 form-control" name="product_ids[]">
                                        <option value="{{ $orderProduct->id }}" selected>
                                            {{ $orderProduct->name }}
                                        </option>
                                        @foreach ($products as $product)
                                            {{-- <option value="{{ $product->id }}">{{ $product->name }}</option> --}}
                                            @if ($product->id !== $orderProduct->id)
                                              <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantities[]" class="form-control"
                                        value="{{ $orderProduct->order_products->quantity}}" placeholder="Quantity">
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-1">
                                <div class="line-item-actions">
                                <button type="button" class="btn btn-primary add-product line-item-btn line-item-btn--add">
                                    <i class="fas fa-plus"></i>
                                </button>
                                </div>
                            </div>
                          {{--   <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-product" style="margin-top:30px;">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div> --}}
                        </div>
                    @endforeach
                </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Discount & VAT -->
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



        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="apply_vat" id="applyVAT" value="1">
                        <label class="form-check-label" for="applyVAT">
                            Apply VAT
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                    <div class="form-group" >
                        <label class="col-form-label" for="inputSuccess">Select Currency  </label>
                        <select class="select2" name="currency_id"  style="width: 50%;">
                            <option value="{{ $order->invoice->currency_id }}" selected>{{ $order->invoice->currency->name}}</option>
                            @foreach($currencies as $currency)

                                @if ($currency->id != $order->invoice->currency_id)
                                   <option value="{{ $currency->id }}" @if($loop->first) selected @endif>{{ $currency->name}}</option>
                                @endif

                            @endforeach
                       </select>
                    </div>
                </div>
        </div>

                <div class="form-submit-row">
                    <button type="submit" class="btn btn-outline-primary form-submit-btn">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagescripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.select2').select2();

    $(document).on('click', '.add-product', function() {
        $('#prodContainer').append(`
            <div class="row product-row line-item-card">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product</label>
                        <select class="select2 form-control" name="product_ids[]">
                            <option value="">Select product....</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantities[]" class="form-control" placeholder="Quantity">
                    </div>
                </div>

                <div class="col-md-2 col-lg-1">
                    <div class="line-item-actions">
                    <button type="button" class="btn btn-primary add-product line-item-btn line-item-btn--add">
                        <i class="fas fa-plus"></i>
                    </button>
                    </div>
                </div>
                <div class="col-md-2 col-lg-1">
                    <div class="line-item-actions">
                    <button type="button" class="btn btn-danger remove-product line-item-btn line-item-btn--remove">
                        <i class="fas fa-minus"></i>
                    </button>
                    </div>
                </div>
            </div>
        `);
        $('.select2').select2();
    });

    $(document).on('click', '.remove-product', function() {
        $(this).closest('.product-row').remove();
    });

    $('#applyDiscount').change(function() {
        $('#discountInput').toggle(this.checked);
    });
});
</script>
@endsection
