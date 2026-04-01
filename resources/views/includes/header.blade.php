<div class="container-fluid">
    <div class="dashboard-topbar">
        <div class="dashboard-topbar__left">
            <a class="dashboard-topbar__menu" data-widget="pushmenu" href="#" role="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </div>

        <div class="dashboard-topbar__right">
            @guest
                @if (Route::has('login'))
                    <a class="dashboard-topbar__ghost" href="{{ route('login') }}">{{ __('Login') }}</a>
                @endif
            @else
                <a href="{{ Auth::user()->role_id == 1 ? route('admin') : route('seller') }}" class="dashboard-topbar__brand">
                    <span class="dashboard-topbar__brand-mark">N</span>
                    <span>
                        <span class="dashboard-topbar__brand-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                        <span class="dashboard-topbar__brand-subtitle">{{ Auth::user()->role_id == 1 ? 'Administrator' : 'Seller' }}</span>
                    </span>
                </a>

                <div class="nav-item dropdown">
                    <a id="navbarDropdown" class="dashboard-topbar__profile dropdown-toggle" href="#"
                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span class="dashboard-topbar__avatar">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                        </span>
                        <span class="dashboard-topbar__profile-copy">
                            <span class="dashboard-topbar__profile-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                            <span class="dashboard-topbar__profile-role">{{ Auth::user()->role_id == 1 ? 'Administrator' : 'Seller' }}</span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('change') }}">
                            <i class="fas fa-lock mr-2"></i>{{ __('Change password') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>
