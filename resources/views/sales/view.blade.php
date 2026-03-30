@extends('layouts.master')

@section('content')
<br>
    <div class="container-fluid">

            <div class="card card-default ">
                <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cart-plus"></i>  Sales Description Details.</h3>
                            <small class="float-right">
                               {{--  <a href="{{route('salesControlBackButton')}}">
                                     <button type="button" class="btn btn-success btn-sm"><i class="fas fa-arrow-left " style="color:white;"></i> Back</button>
                                </a> --}}
                            </small>
                </div>
                <div class="card-body">

                    <br>
                    @php
                    $i=0;
                    @endphp

                    <!-- First Table row -->
                    <div class="row">
                      <div class="col-12 table">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Invoice Number</th>
                              <th>Customer Name</th>
                              <th>Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                                  <tr>
                                    <td> {{$sale->invoice->invoice_number }} </td>
                                    <td> {{$sale->customer->name}} </td>
                                    <td> {{$sale->total_amount}} </td>
                                  </tr>


                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="row">
                        <div class="col-12 " >
                            <div class="form-group"  style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 0px;">
                                <label>Comment</label>
                                <input type="textarea" name="comment" style="grid-column: span 2; width: 100%; height: 100px;" class="form-control" value="{{$sale->comment}}" disabled>
                            </div>
                        </div>
                    </div>
<br>
                    <div class="row">
                        <div class="col-12 ">
                            <a href="{{route('salesControlBackButton')}}">
                                <button type="button" class="btn btn-success btn-md"><i class="fas fa-arrow-left " style="color:white;"></i> Back</button>
                           </a>
                            <button type="button" class="btn btn-outline-success btn-md float-right" data-toggle="modal"
                            data-target="#modal-update{{ $sale->id }}"><i class="fas fa-edit fa-xs">
                            </i> Edit</button>
                        </div>
                    </div>

                </div>
            </div>

    </div>


      <!-- update modal-->
      <div class="modal fade" id="modal-update{{$sale->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{route('updateSales',$sale->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editing Sales Record </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <input type="text" name="customer" class="form-control" value="{{$sale->customer->name}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Order Number</label>
                                    <input type="text" name="order_number" class="form-control" value="{{$sale->order->order_number}}" disabled >
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input type="text" name="invoice_number" class="form-control" value="{{$sale->invoice->invoice_number}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" value="{{$sale->total_amount}}"  >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comment</label>
                                    <input type="text" name="comment" class="form-control" value="">
                                </div>
                            </div>

                        </div>
                </div>
                <div class="modal-footer justify-content-between">

                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>

                        <button type="submit" class="btn btn-outline-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('pagescripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

<script type="text/javascript">

$(function() {

    $('.select2').select2()
                setTimeout(function() {
                    $("#success_element").hide();
        },
    2000);
});


</script>
@endsection
