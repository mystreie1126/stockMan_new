  <nav class="blue darken-2">

      <div class="nav-wrapper">
        <a href="{{route('salespage')}}" class="brand-logo">StockManager</a>
        <a href="#" data-activates="side-nav" class="button-collapse show-on-large right">
          <i class="material-icons">menu</i>
        </a>
        <ul class="right">
           {{--  <li>
              <a href="{{route('salespage')}}">Shop Sales</a>
            </li>
            <li>
              <a href="{{route('stockpage')}}">Check Stock</a>
            </li> --}}



            <li>
              @guest
                  <li><a href="{{ route('login') }}">Login</a></li>
              @else
                  <ul>
                      <li>
                          <a href="">StockOut</a>
                      </li>
                      {{-- @if(Auth::User()->HQ == 1)
                          <li>
                              <a class='dropdown-button' href='#' data-activates='dropdown1'>Stock In</a>

                          </li>
                      @endif --}}
                      <li>
                          <a href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                              Logout
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                          </form>
                      </li>
                  </ul>
              @endguest
            </li>
        </ul>
        <!-- Side nav -->

        <ul id="side-nav" class="side-nav">
          <p></p>
          <li class="center">
              <a>
                  <span style="font-size:1.6rem">
                      <span class="red-text">Fun</span><span class="grey-text">Tech</span> StockManager</span>
                  </span>
              </a>
          </li>
          @if(Auth::check())
             <li class="center">
               <a class="indigo-text">Login As: {{Auth::User()->name}}</a>
             </li>

              <li>
                <a><span class="nav_sub_header amber-text">StockTake</span></a>
              </li>

              <li>
                <a href="{{route('HQ_stockTake')}}">My StockTake</a>
              </li>

              <li>
                <a href="{{route('mystocktake')}}">StockTake Records</a>
              </li>

              <li>
                <a href="{{route('stockTake_analysis')}}">StockTake Analysis</a>
              </li>

              <li>
                <div class="divider">User Action</div>
              </li>

           <!-- stock In-->
              <li>
                <a><span class="nav_sub_header red-text">Stock In</span></a>
              </li>
                 <li>
                  <a href="{{route('accumulate_stock')}}">Accumulate Stock</a>
                 </li>

                 <li>
                  <a href="#">New Product</a>
                 </li>

                 <li>
                  <a href="#">Stock In History</a>
                 </li>
              <li>
                 <div class="divider">User Action</div>
              </li>
            <!-- stock Out-->
              <li>
                <a><span class="nav_sub_header indigo-text">Stock Out</span></a>
              </li>

                  <li>
                    <a href="{{route('replishment')}}">Re-inStock</a>
                  </li>

                  <li>
                    <a href="#">Re-inStock History</a>
                  </li>

                  <li>
                    <a href="{{route('rep_update')}}">Update to Branches</a>
                  </li>

              <li>
                <div class="divider">User Action</div>
              </li>

              <!-- Manage Devices -->
              <li>
                <a href="#"><span class="nav_sub_header purple-text text-darken-1">Manage Devices</span></a>
              </li>

                  <li>
                    <a href="{{route('newDeviceStockIn')}}">Devices Stock In</a>
                  </li>

                  <li>
                    <a href="{{route('transferDevices')}}">Transfer Devices</a>
                  </li>

                  <li>
                    <a href="{{route('sendDevice')}}">Ready To Send</a>
                  </li>

          @else
              <li class="center">
                <a>Please login</a>
              </li>
          @endif
        </ul>

    </div>
  </nav>
