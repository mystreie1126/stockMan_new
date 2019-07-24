  <nav class="blue darken-2">

      <div class="nav-wrapper">
        <a href="{{route('salespage')}}" class="brand-logo">StockManager</a>
        <a href="#" data-activates="side-nav" class="button-collapse show-on-large right">
          <i class="material-icons">menu</i>
        </a>
        <ul class="right">

            <li>
              @guest
                  <li><a href="{{ route('login') }}">Login</a></li>
              @else
                  <li>
                    <a href="{{route('parts_barcode')}}">Add Barcode on Parts</a>
                  </li>
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

             <li class="teal">
               <a><span class="nav_sub_header white-text">Partner Order</span></a>
             </li>

             <li>
               <a href="{{route('partner_order')}}">Partner Order</a>
             </li>

             <li>
               <div class="divider">User Action</div>
             </li>

              <li class="amber">
                <a><span class="nav_sub_header white-text">StockTake</span></a>
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
              <li class="red">
                <a><span class="nav_sub_header white-text">Stock In</span></a>
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
              <li class="indigo">
                <a><span class="nav_sub_header white-text">Stock Out</span></a>
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
              <li class="purple darken-1">
                <a href="#"><span class="nav_sub_header white-text">Manage Devices</span></a>
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

                  <li>
                    <div class="divider">User Action</div>
                  </li>


                  <li>
                      <a href="{{ route('logout') }}" class="btn"
                          onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                          LOG OUT
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                      </form>
                  </li>

          @else
              <li class="center">
                <a>Please login</a>
              </li>
          @endif
        </ul>

    </div>
  </nav>
