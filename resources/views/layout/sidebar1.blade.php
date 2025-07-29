<div class="br-logo">
  <a href=""><span>{{ __('general.'.(Auth::user()->type->name == "RO" ? 'app_name_ro' : (Auth::user()->type->name == "staff" ? 'app_name_staff' : 'app_name_admin'))) }}</span></a>
</div>
  <div class="br-sideleft sideleft-scrollbar" style="background-color: #18355D;">
    <ul class="br-sideleft-menu my-3" id="accordionSidebar">

    @foreach($arr_sidebar['arr_sidebar'] as $sidebar)
      @if(!empty($sidebar["for_label"]) && $sidebar["for_label"])
        <label class="sidebar-label pd-x-10 mg-t-20 op-3">{{ __($sidebar["name"]) }}</label>
      @else
        <li class="br-menu-item">
          @if(!empty($sidebar['arr']))
            <a href="#" class="br-menu-link with-sub {{ Request::is($sidebar['url'].'*') ? 'active' : '' }}">
              <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
              <span class="menu-item-label">{{ __($sidebar['name']) }}</span>
            </a>
            <ul class="br-menu-sub">
              @foreach($sidebar['arr'] as $data)
                <li class="sub-item"><a href="{{ $data['href'] }}" @click="on_sidebar_clicked" class="sub-link {{ Request::is($data['url'].'*') ? 'active' : '' }}">{{ __($data['name']) }}</a></li>
              @endforeach
            </ul>
          @else
            <a href="{{ $sidebar['href'] }}" class="br-menu-link {{ Request::is($sidebar['url'].'*') ? 'active' : '' }}" @click="on_sidebar_clicked">
              <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
              <span class="menu-item-label">{{ __($sidebar['name']) }}</span>
            </a>
          @endif
        </li>
      @endif
    @endforeach
    </ul>
  </div>

  <!-- Sidebar Toggler (Sidebar) -->
  {{-- <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div> --}}

</div>

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
