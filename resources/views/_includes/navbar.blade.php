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
                    <a href="{{route('demon_stockCheck')}}">demo</a>
                  </li>
                  {{-- <li>
                    <a href="{{route('phone_check')}}" class="amber-text">Device and Parts Check</a>
                  </li> --}}
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
            
            <li class="light-green accent-3">
              <a><span class="nav_sub_header white-text">Charts</span></a>
            </li>

             <li>
              <a href="{{route('track_product_info_chart')}}">Product Chart</a>
            </li>

             <li>
              <a href="{{route('salespage')}}">Sold percentage Chart</a>
            </li>
            <li>
              <div class="divider">User Action</div>
            </li>
            

            <li class="teal darken-4">
              <a><span class="nav_sub_header white-text">Standards and stock</span></a>
            </li>

             <li>
              <a href="{{route('detail_product_all')}}">Product By Standard</a>
            </li>

            
            <li>
              <div class="divider">User Action</div>
            </li>


            <li class="deep-purple accent-1">
              <a><span class="nav_sub_header white-text">Cross Check</span></a>
            </li>

            <li>
              <a href="{{route('crosscheck')}}">check pages</a>
            </li>

            
            <li>
              <div class="divider">User Action</div>
            </li>

             <li class="black">
              <a><span class="nav_sub_header white-text">Devices and Parts</span></a>
            </li>

            {{-- <li>
              <a href="{{route('phone_check')}}">Check with excel sheet</a>
            </li> --}}
            <li>
              <a href="{{route('track_Parts_by_Standard')}}">Track Parts by Standard</a>
            </li>
           
            @if(Auth::User()->id == 1 || Auth::User()->id == 4 || Auth::User()->id == 6)
                <li>
                  {{-- <a href="{{route('edit_parts')}}" style="display:flex; align-items:center">
                      <span>Edit Parts Quantity</span>
                      <i class="small material-icons  red-text text-darken-3">star</i>
                  </a> --}}
                  <a href="{{route('parts_uploaded')}}" style="display:flex; align-items:center">
                      <span>uploaded parts SS sheet</span>
                      <i class="small material-icons  red-text text-darken-3">build</i>
                  </a>
                </li>
                <li>
                    <a href="{{route('pop_stockTake')}}" style="display:flex; align-items:center">
                        <span>Check Branch Pop SS Sheet</span>
                        <i class="small material-icons  red-text text-darken-3">phone_android</i>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{route('sm1_parts_upload_history')}}">Parts Upload&Merge History</a>
              </li>
            <li>
              <div class="divider">User Action</div>
            </li>

             <li class="green">
               <a><span class="nav_sub_header white-text">Delivery Cost</span></a>
             </li>

             <li>
               <a href="{{route('partner_delivery_prices')}}">View Price</a>
             </li>

             <li>
               <div class="divider">User Action</div>
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
