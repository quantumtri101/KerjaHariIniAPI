<ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color: #18355D;" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <img class="img-profile rounded-circle" src="{{ !empty(Auth::user()->file_name) && Auth::user()->file_name != '' ? url('/image/user?file_name='.Auth::user()->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}">
      <span class="ml-2 small text-white">{{ Auth::user()->name }}</span>
    </a>
    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-left shadow" aria-labelledby="userDropdown">
      <a class="dropdown-item" href="{{ url('/auth/change-profile') }}">
        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
        {{ __('general.profile') }}
      </a>
      <a class="dropdown-item" href="{{ url('/auth/change-password') }}">
        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
        {{ __('general.change_password') }}
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="{{ url('/auth/logout') }}">
        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
        {{ __('general.logout') }}
      </a>
    </div>
  </li>

  @foreach($arr_sidebar['arr_sidebar'] as $sidebar)
    <li class="nav-item {{ Request::is($sidebar['url'].'*') ? 'active' : '' }} p-2">
      @if(!empty($sidebar['arr']))
        <a class="nav-link p-2 w-auto {{ Request::is($sidebar['url'].'*') ? '' : 'collapsed' }}"
          href="{{ $sidebar['href'] }}"
          data-toggle="collapse"
          data-target="#{{ $sidebar['id'] }}"
          aria-expanded="true"
          aria-controls="{{ $sidebar['id'] }}">
          <i class="fas fa-fw {{ __($sidebar['icon']) }}"></i>
          <span style="color: #FFFFFF">{{ __($sidebar['name']) }}</span>
        </a>
        <div id="{{ $sidebar['id'] }}"
          class="collapse m-0 {{ Request::is($sidebar['url'].'*') ? 'show' : '' }}"
          aria-labelledby="headingPages"
          data-parent="#accordionSidebar">
          <div class=" py-2 collapse-inner rounded">
            @foreach($sidebar['arr'] as $data)
              <a class="collapse-item {{ Request::is($data['url'].'*') ? 'active' : '' }}"
                href="{{ $data['href'] }}"
                @click="on_sidebar_clicked">{{ __($data['name']) }}</a>
            @endforeach
          </div>
        </div>
      @else
        <a class="nav-link w-auto p-2" href="{{ $sidebar['href'] }}" @click="on_sidebar_clicked">
          <i class="fas fa-fw {{ __($sidebar['icon']) }}" style="color: #FFFFFF"></i>
          <span style="color: #FFFFFF">{{ __($sidebar['name']) }}</span>
        </a>
      @endif
    </li>
  @endforeach

  <!-- Sidebar Toggler (Sidebar) -->
  {{-- <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div> --}}

</ul>

@push('script')
  <script>
    var sidebar = new Vue({
      el: '#accordionSidebar',
      data: {
        arr_sidebar: []
      },
      created(){
        this.arr_sidebar = JSON.parse(('{{ $arr_sidebar['json_arr_sidebar'] }}').replace(/&quot;/g,'"'))
      },
      methods:{
        on_sidebar_clicked(){
          reset_page_stack()
        }
      }
    })
  </script>
@endpush
