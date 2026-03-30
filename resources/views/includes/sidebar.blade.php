  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4" >
      <!-- Brand Logo -->

      <a href="{{route('admin')}}" class="brand-link" style="background-color: #3c8dbc">
        <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Naret Company</span>
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


     {{--  side bar menu options for seller  --}}
               @elseif (Auth::check() && Auth::user()->role_id == 2)
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
