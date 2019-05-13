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
              <a href="{{route('orderpage')}}">Check Orders</a>
            </li>
            <li>
              <a href="{{route('replishment')}}">Replishment</a>
            </li>
            <li>
              <a href="{{route('countstock')}}">Count Stock</a>
            </li>

            <li>
              @guest
                  <li><a href="{{ route('login') }}">Login</a></li>
                  {{-- <li><a href="{{ route('register') }}">Register</a></li> --}}
              @else

                      {{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                          {{ Auth::user()->name }} <span class="caret"></span>
                      </a> --}}

                      <ul class="dropdown-menu">
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
              <a href="#">
                <span class="name white-text">Admin</span>
              </a>
              <a href="#">
                <span class="email white-text">Warehouse@funtech.ie</span>
              </a>
            </div>
          </li>
       {{--    <li>
            <a href="{{route('salespage')}}">Shop Sales</a>
          </li>
          <li>
            <a href="{{route('stockpage')}}">Check Stock</a>
          </li> --}}
          <li>
            <a href="{{route('orderpage')}}">Check Orders</a>
          </li>
          <li>
            <a href="{{route('replishment')}}">Replishment</a>
          </li>
           <li>
              <a href="{{route('countstock')}}">Count Stock</a>
            </li>
         {{--  <li>
            <a href="rep.html">Replishment</a>
          </li>
          <li>
            <a href="users.html">Partners</a>
          </li> --}}
          <li>
            <div class="divider"></div>
          </li>
          <li>
            <a class="subheader">Profile</a>
          </li>
          <li>
            <a href="login.html" class="waves-effect">Logout</a>
          </li>
        </ul>

    </div>
  </nav>
