  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4" >
      <!-- Brand Logo -->

      <a href="{{ Auth::check() && Auth::user()->role_id == 1 ? route('admin') : route('seller') }}" class="brand-link">
        <span class="brand-link__mark">N</span>
        <span class="brand-text font-weight-light">
            <span class="brand-link__name">Naret Company</span>
            <span class="brand-link__subtext">Operations panel</span>
        </span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar Menu -->
          <!-- Sidebar user panel (optional) -->
          <!-- Sidebar user panel (optional) -->



          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="true" style="color:black">
                  <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               @if (Auth::check() && Auth::user()->role_id == 1)
                <li class="nav-item has-treeview ">
                    <a href="{{route('measurement.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-balance-scale-right"></i>
                        <p>
                            Unit of Measurements

                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{route('stocks.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Stock Management

                        </p>
                    </a>
                </li>

              <li class="nav-item has-treeview ">
                            <a href="{{route('products.index')}}" class="nav-link">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>
                                    Products
                                </p>
                            </a>
                        </li>

                <li class="nav-item has-treeview ">
                    <a href="{{route('company.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-city"></i>
                        <p>
                            Companies

                        </p>
                    </a>
                </li>

               <li class="nav-item has-treeview ">
                <a href="{{route('suppliers.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>
                        Suppliers

                    </p>
                </a>
            </li>

                <li class="nav-item has-treeview ">
                    <a href="{{route('accounts.index')}}" class="nav-link">
                        <i class="nav-icon far fa-credit-card"></i>
                        <p>
                            Accounts

                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{route('sales.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Sales Record
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{route('expenses.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-donate"></i>
                        <p>
                            Expenses
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{route('transactions.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>
                            Transactions
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{route('casual_labour.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-people-carry"></i>
                        <p>
                            Casual Labour
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{route('reports')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-excel"></i>
                        <p>
                           Reports
                        </p>
                    </a>
                </li>

                <li class="nav-header">SETTINGS</li>
                <li class="nav-item has-treeview ">
                    <a href="{{ route('settings.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            System Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            User Management
                        </p>
                    </a>
                </li>


     {{--  side bar menu options for seller  --}}
               @elseif (Auth::check() && Auth::user()->role_id != 1)
               <li class="nav-item has-treeview ">
                   <a href="{{route('customers.index')}}" class="nav-link">
                       <i class="nav-icon fas fa-users"></i>
                       <p>
                           Customers

                       </p>
                   </a>
               </li>


              {{-- <li class="nav-item has-treeview ">
                <a href="{{route('products.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-coins"></i>
                    <p>
                        Products
                    </p>
                </a>
            </li> --}}

               <li class="nav-item has-treeview ">
                    <a href="{{route('orders.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>
                           Product Orders
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview ">
                    <a href="{{route('fumigation.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-air-freshener"></i>
                        <p>
                           Fumigation Service
                        </p>
                    </a>
                </li>


               <li class="nav-item has-treeview ">
                   <a href="{{route('invoices.index')}}" class="nav-link">
                       <i class="nav-icon fas fa-file-alt"></i>
                       <p>
                           Invoices
                       </p>
                   </a>
               </li>

               <li class="nav-item has-treeview ">
                <a href="{{route('expenses.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-donate"></i>
                    <p>
                        Expenses
                    </p>
                </a>
            </li>
          {{--   <li class="nav-item has-treeview ">
                <a href="{{route('transactions.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                        Transactions
                    </p>
                </a>
            </li> --}}
            <li class="nav-item has-treeview ">
                <a href="{{route('casual_labour.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-people-carry"></i>
                    <p>
                        Casual Labour
                    </p>
                </a>
            </li>


              @endif


              </ul>

          </nav>


      </div>
      <!-- /.sidebar -->
  </aside>
