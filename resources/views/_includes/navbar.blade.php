  <nav class="blue darken-2">
    <div class="container">
      <div class="nav-wrapper">
        <a href="index.html" class="brand-logo">StockManager</a>
        <a href="#" data-activates="side-nav" class="button-collapse show-on-large right">
          <i class="material-icons">menu</i>
        </a>
        <ul class="right hide-on-med-and-down">
          <li class="active">
            <a href="{{route('homepage')}}">Dashboard</a>
          </li>
          <li>
            <a href="{{route('productpage')}}">Products</a>
          </li>
          <li>
            <a href="{{route('orderpage')}}">Orders</a>
          </li>
          {{-- <li>
            <a href="rep.html">Replishment</a>
          </li>
          <li>
            <a href="users.html">Partners</a>
          </li> --}}
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
          <li>
            <a href="{{route('homepage')}}">
              <i class="material-icons">dashboard</i> Dashboard</a>
          </li>
          <li>
            <a href="{{route('productpage')}}">Products</a>
          </li>
          <li>
            <a href="{{route('orderpage')}}">Orders</a>
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
    </div>
  </nav>