<div class="br-header">
    <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="fa-solid fa-bars"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="fa-solid fa-bars"></i></a></div>
        {{-- <div class="input-group hidden-xs-down wd-170 transition">
          <input id="searchbox" type="text" class="form-control" placeholder="Search">
          <span class="input-group-btn">
            <button class="btn btn-secondary" type="button"><i class="fas fa-search"></i></button>
          </span>
        </div><!-- input-group --> --}}
    </div><!-- br-header-left -->

    <nav class="nav">
        <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <div class="d-flex align-items-center">
                <div class="mr-1">
                  <div class="logged-name hidden-md-down d-block">{{ Auth::user()->name }}</div>
                  @if(Auth::user()->type->name != "admin")
                    <div class="logged-name hidden-md-down  d-block">{{ Auth::user()->company->name }}</div>
                  @endif
                </div>
                <img src="{{ !empty(Auth::user()->file_name) ? url('/image/user?file_name='.Auth::user()->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}" class="wd-32 rounded-circle" alt="">
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-250">
              <div class="tx-center mt-3">
                <a href=""><img src="{{ !empty(Auth::user()->file_name) ? url('/image/user?file_name='.Auth::user()->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}" class="wd-80 rounded-circle" alt=""></a>
                <h6 class="logged-fullname">{{ Auth::user()->name }}</h6>
                <p>{{ Auth::user()->email }}</p>
              </div>
              <hr>
              <ul class="list-unstyled user-profile-nav">
                <li><a href="{{ url('/auth/change-password') }}"><i class="icon ion-ios-person"></i> {{ __('general.change_password') }}</a></li>
                <li><a href="{{ url('/auth/logout') }}"><i class="icon ion-power"></i> {{ __('general.logout') }}</a></li>
              </ul>
            </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
    </nav>
</div>
