  <nav class="blue darken-2">

      <div class="nav-wrapper">
        <a href="{{route('salespage')}}" class="brand-logo">StockManager</a>
        <a href="#" data-activates="side-nav" class="button-collapse show-on-large right">
          <i class="material-icons">menu</i>
        </a>
        <ul class="right hide-on-med-and-down">
           {{--  <li>
              <a href="{{route('salespage')}}">Shop Sales</a>
            </li>
            <li>
              <a href="{{route('stockpage')}}">Check Stock</a>
            </li> --}}


            <li>
              <a href="{{route('HQ_stockTake')}}" class="amber-text">StockTake</a>
            </li>

            <li>
              <a href="{{route('orderpage')}}">Check Orders</a>
            </li>
            <li>
              <a href="{{route('replishment')}}">Replishment</a>
            </li>

            <li>
              @guest
                  <li><a href="{{ route('login') }}">Login</a></li>
              @else
                  <ul>
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

              <!-- stock In-->
              <li>
                <a href="#"><span class="nav_sub_header green-text">Stock In</span></a>
              </li>

          @else
              <li class="center">
                <a>Please login</a>
              </li>
          @endif
        </ul>

    </div>
  </nav>
