@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.dashboard'),
    ],
    "title" => __('general.dashboard'),
  ])
@endsection

@section('content')
  <div>
    <div>
      <h3>Hi, {{ Auth::user()->name }}</h3>
      <p class="m-0">{{ \Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</p>
    </div>

    @if(Auth::user()->type->name == "staff")
      <div class="card mt-3">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <p class="m-0">{{ __('general.recruit_approve') }}</p>
            <a href="{{ url('/jobs') }}">
              <p class="m-0 text-danger">{{ __('general.see_more') }}</p>
            </a>
          </div>

          @if(count($arr_jobs) > 0)
            <div class="row mt-3">
              <div class="col-12 col-lg-6">
                <div class="list-group">
                  @foreach($arr_jobs as $key => $jobs)
                    @if($key <= count($arr_jobs) / 2)
                      <div class="list-group-item pd-y-15 pd-x-20 d-xs-flex align-items-center justify-content-start">
                        <img src="{{ asset('image/jobs_icon.png') }}" class="wd-48 rounded-circle" alt="">
                        <div class="mg-xs-l-15 mg-t-10 mg-xs-t-0 mg-r-auto">
                          <p class="mg-b-0 tx-inverse tx-medium">{{ $jobs->name }}</p>
                          <span class="d-block tx-13">{{ $jobs->id }} {{ $jobs->created_at->formatLocalized('%d %B %Y %H:%m') }}</span>
                        </div>
                        <div class="d-flex align-items-center mg-t-10 mg-xs-t-0">
                          <a href="{{ url('/jobs/detail?id='.$jobs->id) }}" onclick="save_current_page('{{ __('general.dashboard') }}')" class="btn btn-outline-light btn-icon">
                            <div class="tx-20"><i class="icon ion-android-chat"></i></div>
                          </a>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="list-group">
                  @foreach($arr_jobs as $key => $jobs)
                    @if($key > count($arr_jobs) / 2)
                      <div class="list-group-item pd-y-15 pd-x-20 d-xs-flex align-items-center justify-content-start">
                        <img src="{{ asset('image/jobs_icon.png') }}" class="wd-48 rounded-circle" alt="">
                        <div class="mg-xs-l-15 mg-t-10 mg-xs-t-0 mg-r-auto">
                          <p class="mg-b-0 tx-inverse tx-medium">{{ $jobs->name }}</p>
                          <span class="d-block tx-13">{{ $jobs->id }} {{ $jobs->created_at->formatLocalized('%d %B %Y %H:%m') }}</span>
                        </div>
                        <div class="d-flex align-items-center mg-t-10 mg-xs-t-0">
                          <a href="{{ url('/jobs/detail?id='.$jobs->id) }}" onclick="save_current_page('{{ __('general.dashboard') }}')" class="btn btn-outline-light btn-icon">
                            <div class="tx-20"><i class="icon ion-android-chat"></i></div>
                          </a>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          @else
            <p class="m-0 text-center mt-3">{{ __('general.no_data') }}</p>
          @endif
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-12 col-lg-3">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_recruit_approve') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_inactive_jobs, 0, ',', '.') }}</p>
                <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span>
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>

        <div class="col-12 col-lg-3">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_active_jobs') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_active_jobs, 0, ',', '.') }}</p>
                {{-- <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span> --}}
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>

        <div class="col-12 col-lg-3">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_staff_regular') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_customer_regular, 0, ',', '.') }}</p>
                <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span>
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>

        <div class="col-12 col-lg-3">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_staff_oncall') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_customer_oncall, 0, ',', '.') }}</p>
                <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span>
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>
      </div>
    @elseif(Auth::user()->type->name == "RO")
      <div class="card mt-3">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <p class="m-0">{{ __('general.jobs') }}</p>
            <a href="{{ url('/jobs') }}">
              <p class="m-0 text-danger">{{ __('general.see_more') }}</p>
            </a>
          </div>

          @if(count($arr_jobs) > 0)
            <div class="row mt-3">
              <div class="col-12 col-lg-6">
                <div class="list-group">
                  @foreach($arr_jobs as $key => $jobs)
                    @if($key <= count($arr_jobs) / 2)
                      <div class="list-group-item pd-y-15 pd-x-20 d-xs-flex align-items-center justify-content-start">
                        <img src="{{ asset('image/jobs_icon.png') }}" class="wd-48 rounded-circle" alt="">
                        <div class="mg-xs-l-15 mg-t-10 mg-xs-t-0 mg-r-auto">
                          <p class="mg-b-0 tx-inverse tx-medium">{{ $jobs->name }}</p>
                          <span class="d-block tx-13">{{ $jobs->id }} {{ $jobs->created_at->formatLocalized('%d %B %Y %H:%m') }}</span>
                        </div>
                        <div class="d-flex align-items-center mg-t-10 mg-xs-t-0">
                          <a href="{{ url('/jobs/detail?id='.$jobs->id) }}" onclick="save_current_page('{{ __('general.dashboard') }}')" class="btn btn-outline-light btn-icon">
                            <div class="tx-20"><i class="icon ion-android-chat"></i></div>
                          </a>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="list-group">
                  @foreach($arr_jobs as $key => $jobs)
                    @if($key > count($arr_jobs) / 2)
                      <div class="list-group-item pd-y-15 pd-x-20 d-xs-flex align-items-center justify-content-start">
                        <img src="{{ asset('image/jobs_icon.png') }}" class="wd-48 rounded-circle" alt="">
                        <div class="mg-xs-l-15 mg-t-10 mg-xs-t-0 mg-r-auto">
                          <p class="mg-b-0 tx-inverse tx-medium">{{ $jobs->name }}</p>
                          <span class="d-block tx-13">{{ $jobs->id }} {{ $jobs->created_at->formatLocalized('%d %B %Y %H:%m') }}</span>
                        </div>
                        <div class="d-flex align-items-center mg-t-10 mg-xs-t-0">
                          <a href="{{ url('/jobs/detail?id='.$jobs->id) }}" onclick="save_current_page('{{ __('general.dashboard') }}')" class="btn btn-outline-light btn-icon">
                            <div class="tx-20"><i class="icon ion-android-chat"></i></div>
                          </a>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          @else
            <p class="m-0 text-center mt-3">{{ __('general.no_data') }}</p>
          @endif
        </div>
      </div>
      
      <div class="row mt-3">
        <div class="col-12 col-lg-3 h-100">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_worker_oncall') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_worker_oncall, 0, ',', '.') }}</p>
                <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span>
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>

        <div class="col-12 col-lg-3 h-100">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_worker_regular') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_worker_regular, 0, ',', '.') }}</p>
                <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span>
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>

        <div class="col-12 col-lg-3 h-100 mt-3 mt-lg-0">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-y-20 d-flex align-items-center">
              <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ __('general.total_jobs') }}</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">{{ number_format($total_active_jobs, 0, ',', '.') }}</p>
                <span class="tx-11 tx-roboto tx-white-8">{{ __('general.this_month') }}</span>
              </div>
            </div>
            {{-- <div id="ch1" class="ht-50 tr-y-1"></div> --}}
          </div>
        </div>

        <div class="col-12 col-lg-3 h-100 mt-3 mt-lg-0">
          <a class="btn btn-warning w-100" href="{{ url('/jobs/action') }}" onclick="save_current_page('{{ __('general.dashboard') }}')">
            {{ __('general.add_recruit_form') }}
          </a>

          <a class="btn btn-warning w-100 mt-3" href="{{ url('/user/staff') }}">
            {{ __('general.add_user_entity') }}
          </a>
        </div>
      </div>
    @endif
  </div>
@endsection

@push('script')
<script type="text/javascript">

</script>
@endpush