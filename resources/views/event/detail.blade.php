@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.event'),
      __('event.detail'),
    ],
    "title" => __('event.detail'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => __('event.detail'),
  ])

  <div class="mt-3">
    <ul class="nav nav-pills" id="detailTab" role="tablist">
      @foreach($arr_tab as $tab)
        <li class="nav-item" role="presentation">
          <button class="nav-link border-0" id="{{ $tab["id"] }}-tab" data-toggle="tab" data-target="#{{ $tab["id"] }}" type="button" role="tab" onclick="on_tab_clicked('{{ $tab["id"] }}')" aria-controls="{{ $tab["id"] }}" aria-selected="true">{{ __('general.'.$tab["id"]) }}</button>
        </li>
      @endforeach
    </ul>

    <div class="mt-3 d-none" id="filter_container">
      @include('layout.reservation_filter',[
      ])
    </div>
    
    <!-- Tab panes -->
    <div class="tab-content mt-3" id="pills-detailTabContent">
      @foreach($arr_tab as $tab)
      <div class="tab-pane" id="{{ $tab["id"] }}" role="tabpanel" aria-labelledby="{{ $tab["id"] }}-tab">
        <div class="card">
          <div class="card-body">
            @include($tab["component"])
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  @push('script')
    <script>
      function on_tab_clicked(id){
        localStorage.setItem('menu1', id)
        if(id == "calendar")
          reload_calendar()
      }
      
      $(document).ready(async() => {
        var menu = await get_menu_detail('general_info', 'menu1')
          
        localStorage.setItem('menu1', menu)
          
        $('#' + menu + '-tab').addClass('active')
        $('#' + menu).addClass('show active')

        if(menu == "calendar")
          reload_calendar()
      })
    </script>
  @endpush
@endsection
