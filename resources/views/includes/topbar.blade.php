<div class="container">
    @if(Auth::user()->role_id == 1)
    <li class="nav-item">
      <a href="{{route('admin')}}" class="nav-link"><i class="fas fa-home"></i> Home</a>
    </li>
      <li class="nav-item">
        <a href="{{route('methods.index')}}" class="nav-link"><i class="fas fa-flask"></i> Methods</a>
      </li>
      <li class="nav-item">
        <a href="{{route('roles.index')}}" class="nav-link"><i class="fas fa-plus"></i> Roles</a>
      </li>
      <li class="nav-item">
        <a href="{{route('users.index')}}" class="nav-link"><i class='fas fa-users'></i> User Management</a>
      </li>
@endif

    <!-- Right navbar links -->

    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

        <li class="nav-item ">
            <a class="nav-link" href="">wewe</a>
        </li>
        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif
        @else
        <!-- <b>
      <div class="time-label bg-danger" id="time" style="font-size:20px;margin-left:15px;"></div>
    </b> -->

        <li class="nav-item dropdown">

            <a id="navbarDropdown" class="nav-link dropdown-toggle" style="color:white;font-size:14px;" href="#"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i><b><span class="fas fa-user"></span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </b></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" style="font-size:14px;" href="{{ route('change') }}"
                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    <i class="fas fa-lock mr-2"></i> <i> <b> {{ __('Change password') }} </b> </i>
                </a>
                <a class="dropdown-item" style="font-size:14px;" href="{{route('logout')}}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <i class="fas fa-lock mr-2"></i> <i> <b> {{ __('Logout') }} </b> </i>
                </a>
                <form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>


        </li>

        @endguest
    </ul>
</div>
