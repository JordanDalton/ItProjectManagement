    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="{{ URL::to_route('home') }}"></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="divider-vertical"></li>
              <li class=""><a href="{{ URL::to_route('projects') }}"><i class="icon-folder-open"></i> Projects</a></li>
              <li class="divider-vertical"></li>
              <li class=""><a href="{{ URL::to_route('project.create') }}"><i class="icon-plus"></i> Create Project</a></li>
              <li class="divider-vertical"></li>
              @if(Auth::check())
              <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-user"></i> {{ Auth::user()->name }} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="nav-header">Your Stuff</li>
                  <li class=""><a href="{{ URL::to_route('user.profile', My_Auth::user()->id) }}"><i class="icon-eye-open"></i> Profile</a></li>
                  <li class=""><a href="{{ URL::to_route('user.avatar') }}"><i class="icon-camera"></i> Update Avatar</a></li>
                  <li class=""><a href="{{ URL::to_route('user.projects', My_Auth::user()->id) }}"><i class="icon-folder-open"></i> Projects</a></li>
                  <li class="divider"></li>
                  <li><a href="{{ URL::to_route('logout') }}"><i class="icon-off"></i> Log out</a></li>
                </ul>
              </li>
              <li class="divider-vertical"></li>
              @endif
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>