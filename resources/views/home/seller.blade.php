@extends('layouts.master')

@section('content')

<br>

    <section class="content ">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('assignView')}}">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Assign to Fumigator</span>
                                <span class="info-box-number">
                                   {{--  @if ($user_count > 0)
                                   ({{$user_count}})
                                   @endif --}}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('allRejectedSales') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Rejected Sales</span>
                                <span class="info-box-number">
                                   @if ($rejected > 0)
                                   ({{$rejected}})
                                   @else

                                   @endif
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            {{-- other contents --}}
        </div>
    </section>

@endsection
