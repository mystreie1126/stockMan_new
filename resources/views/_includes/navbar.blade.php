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
                  {{-- <li><a href="{{ route('register') }}">Register</a></li> --}}
              @else

                      {{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                          {{ Auth::user()->name }} <span class="caret"></span>
                      </a> --}}

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
          <li>
            <div class="user-view">

              {{-- @if(Auth::check()) --}}
              {{-- <a href="#">
                <span class="email white-text">{{Auth::User()->name}}</span>
              </a> --}}
              {{-- @endif --}}
            </div>
          </li>
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
          @endif
          <li>
            <a href="{{route('HQ_stockTake')}}"><span class="nav_sub_header amber-text">StockTake</span></a>
          </li>

              <li>
                <a href="{{route('mystocktake')}}">My StockTake</a>
              </li>

              <li>
                <a href="#">StockTake History</a>
              </li>
          <li>
            <div class="divider">User Action</div>
          </li>
          <li>
            <a href="{{route('replishment')}}"><span class="nav_sub_header green-text">Stock Out</span></a>
          </li>

              <li>
                <a href="#">Re-inStock</a>
              </li>
              <li>
                <a href="#">Re-inStock History</a>
              </li>
              <li>
                <a href="#">Top Sales</a>
              </li>

          <li>
            <div class="divider">User Action</div>
          </li>



          <li>
            <a href="{{route('login')}}" class="waves-effect">Logout</a>
          </li>
        </ul>

    </div>
  </nav>
