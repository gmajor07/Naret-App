
    <!-- Left navbar links -->
    <ul class="navbar-nav" >
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color:white;"></i></a>
      </li>
     {{--  <li class="nav-item d-none d-sm-inline-block" >
        <a href="index3.html" class="nav-link" style="color:white;">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link" style="color:white;">Contact</a>
      </li> --}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif

            @else
            <li class="nav-item dropdown">

                <a id="navbarDropdown" class="nav-link dropdown-toggle" style="color:white;font-size:14px;" href="#"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i><b><span class="fas fa-user"></span>  {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </b></i>
            </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" style="font-size:14px;" href="{{ route('change') }}">
                        <i class="fas fa-lock mr-2"></i> <i> <b> {{ __('Change password') }} </b> </i>
                    </a>
                    <a class="dropdown-item" style="font-size:14px;" href="{{route('logout')}}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> <i> <b> {{ __('Logout') }} </b> </i>
                    </a>
                    <form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>


            </li>
        @endguest
    </ul>

