@extends('layouts.master')

@section('content')
<br>
    <div class="container-fluid">

            <div class="card card-default ">
                <div class="card-header">
                            <h2 class="card-title">{{-- <i class="fas fa-cart-plus"></i> --}} <b> Usage Description of the Following Fumigation Product{{--{{$order->order_number }} --}} Details.</b></h2>
                            <small class="float-right">
                                <a href="{{route('consumptionfumigationControlBackButton')}}">
                                     <button type="button" class="btn btn-success btn-sm"><i class="fas fa-arrow-left " style="color:white;"></i> Back</button>
                                </a>
                            </small>
                </div>
                <div class="card-body">

                    {{--  <h4>Usage Description of the Following Fumigation Product</h4> --}}
                    1.) <b>{{$consumption->item_quantity}} {{$consumption->product->unit_measure->name }} </b>of  <b>{{$consumption->product->name }}</b> given to <b>{{$consumption->user()->first()->first_name}} {{$consumption->user()->first()->last_name}}</b> on <b>{{ $consumption->created_at->format('d/m/Y') }}</b> <br>
                        &nbsp  &nbsp  &nbsp This product was used as the following table describe.
                    <br>

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
                              <th>Fumigator Name</th>
                              <th>Used In</th>
                              <th>Amount Of Container</th>
                            </tr>
                          </thead>
                          <tbody>
                           @foreach($consumption->fumigations as $fumigation)
                                  <tr>

                                        <td>
                                           {{ $fumigation->user->first()->first_name ?? '' }} {{ $fumigation->user->first()->last_name ?? '' }}
                                        </td>
                                      {{--   <td>{{$consumption->user()->first()->first_name }}</td> --}}
                                        {{-- <td>{{$fumigation->user()->first_name}} </td> --}}
                                      <td> {{$fumigation->description}} </td>
                                        <td> {{$fumigation->pivot->container_quantity}} </td>
                                  </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td ><b>Total Container</b></td>
                                <td><b>
                                    @php
                                    $totalContainerQuantity = $consumption->fumigations->sum('pivot.container_quantity');
                                    @endphp
                                {{ $totalContainerQuantity }}
                                </b></td>
                                <td></td>
                            </tr>
                           {{--  <tr>
                                <td></td>
                                <td><b>UNPAID AMOUNT</b></td>
                                <td style="color: #aa0404;"><b>Tzs. </b></td>
                                <td></td>
                            </tr> --}}
                          </tbody>
                        </table>
                      </div>
                    </div>


                    In summary the <b>{{$consumption->item_quantity}}</b> bottles of <b>{{$consumption->product->name }} </b> were used in <b> {{ $totalContainerQuantity }}</b> containers.
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
