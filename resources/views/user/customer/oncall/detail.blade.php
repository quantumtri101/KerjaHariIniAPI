@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.customer_oncall'),
      __('customer_oncall.detail'),
    ],
    "title" => __('customer_oncall.detail'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => __('customer_oncall.detail'),
  ])

  @if(!empty($jobs_application))
    <div class="card mt-3">
      <div class="card-body">
        <h5>{{ __('general.application') }}</h5>
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.application') }}</label>
          <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $jobs_application->content }}</textarea>
        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status') }}</label>
          <div>
            <span class=" pd-y-3 pd-x-10 tx-white tx-11 tx-roboto" style="background-color: #{{ $jobs_application->status_bg_color }}">{{ $jobs_application->status == "wait" && $jobs_application->is_approve_worker == 0 ? __('general.wait_customer') : $jobs_application->status }}</span>
          </div>
        </div>

        @if($jobs_application->is_approve_corp == 0)
          <div class="row">
            @if($jobs_application->status == 'wait' || $jobs_application->status == 'interview')
              <div class="col-12 col-lg-4">
                <form method="post" action="{{ url('/jobs/application/change-status') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $jobs_application->id }}"/>
                  <input type="hidden" name="status" value="declined"/>
                  <button class="btn btn-primary w-100">{{ __('general.decline') }}</button>
                </form>
              </div>
            @endif
            @if($jobs_application->status == 'wait' && count($jobs_application->jobs->interview) > 0)
              <div class="col-12 col-lg-4">
                <form method="post" action="{{ url('/jobs/application/change-status') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $jobs_application->id }}"/>
                  <input type="hidden" name="status" value="interview"/>
                  <button class="btn btn-primary w-100">{{ __('general.do_interview') }}</button>
                </form>
              </div>
            @elseif($jobs_application->status == 'interview' && count($jobs_application->jobs->briefing) > 0)
              <div class="col-12 col-lg-4">
                <form method="post" action="{{ url('/jobs/application/change-status') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $jobs_application->id }}"/>
                  <input type="hidden" name="status" value="accepted"/>
                  <button class="btn btn-primary w-100">{{ __('general.do_briefing') }}</button>
                </form>
              </div>
            @endif
            @if($jobs_application->status == 'wait' || $jobs_application->status == 'interview')
              <div class="col-12 col-lg-4">
                <form method="post" action="{{ url('/jobs/application/change-status') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $jobs_application->id }}"/>
                  <input type="hidden" name="status" value="accepted"/>
                  <button class="btn btn-primary w-100">{{ __('general.accept') }}</button>
                </form>
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>
  @endif

  <div class="mt-3">
    <ul class="nav nav-pills" id="detailTab" role="tablist">
      @foreach($arr_tab as $tab)
        <li class="nav-item" role="presentation">
          <button class="nav-link border-0" id="{{ $tab["id"] }}-tab" data-toggle="tab" data-target="#{{ $tab["id"] }}" type="button" role="tab" onclick="on_tab_clicked('{{ $tab["id"] }}')" aria-controls="{{ $tab["id"] }}" aria-selected="true">{{ __('general.'.$tab["id"]) }}</button>
        </li>
      @endforeach
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content mt-3" id="pills-detailTabContent">
      @foreach($arr_tab as $tab)
      <div class="tab-pane" id="{{ $tab["id"] }}" role="tabpanel" aria-labelledby="{{ $tab["id"] }}-tab">
        <div class="card">
          <div class="card-body">
            @include($tab["component"], [
              "customer_oncall" => $customer_oncall,
            ])
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
        var menu = await localStorage.getItem('menu')
        if(menu === "" || menu == null)
          menu = "general_info"
          
        localStorage.setItem('menu', menu)
          
        $('#' + menu + '-tab').addClass('active')
        $('#' + menu).addClass('show active')

        
      })
    </script>
  @endpush
@endsection
