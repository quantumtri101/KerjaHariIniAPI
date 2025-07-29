@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.salary_verification'),
      __('salary_verification.detail'),
    ],
    "title" => __('salary_verification.detail'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => __('salary_verification.detail'),
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
        localStorage.setItem('menu', id)
      }
      
      $(document).ready(async() => {
        var menu = await get_menu_detail()
          
        localStorage.setItem('menu', menu)
          
        $('#' + menu + '-tab').addClass('active')
        $('#' + menu).addClass('show active')
      })
    </script>
  @endpush
@endsection
