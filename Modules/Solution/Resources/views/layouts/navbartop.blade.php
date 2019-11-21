  <header class="main-header">
    <!-- Logo -->
    <a href="{{URL::to('/solution')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><strong>Helpdesk</strong> Tools</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><strong>Helpdesk</strong> Tools</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
              <span class="hidden-xs"><?php echo Session::get('fullname'); ?></span>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li><a href="{{ URL::to('/logout')}}">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>