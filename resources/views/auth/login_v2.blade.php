@extends('layout.base_auth')

@section('content')
<div class="d-none d-lg-block">
  <div class="row no-gutters flex-row ht-100v">
    <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #18355D">
      <div class="wd-250 wd-xl-450 mg-y-30">
        <img src="{{ url('/image/public').'?file_name=logo_web.png' }}" style="width: 30rem;"/>
        {{-- <div class="signin-logo tx-28 tx-bold tx-white">
          <span class="tx-normal">[</span> {{ __('general.app_name') }} <span class="tx-normal">]</span>
        </div>
        <div class="tx-white mg-b-60">The Admin Template For Perfectionist</div> --}}

        <h3 class="tx-white mt-5">Find Matching<br/>Worker for<br/>your Job Opening</h3>
      </div><!-- wd-500 -->
    </div>

    <div class="col-md-6 bg-gray-200 d-flex align-items-center justify-content-center">
      <div class="login-wrapper wd-250 wd-xl-350 mg-y-30">
        <h4 class="tx-inverse tx-center">Sign In</h4>

        <div class="mt-5">
          @if(Session::has('message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ Session::get('message') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif

          <form method="post" action="{{ url('/auth/login') }}">
            @csrf
            <input type="hidden" name="type" value="web_admin"/>
            <div class="form-group">
              <input type="email" name="email" required class="form-control" placeholder="Enter your email">
            </div><!-- form-group -->
            <div class="form-group">
              <input type="password" name="password" required class="form-control" placeholder="Enter your password">
              {{-- <a href="" class="tx-info tx-12 d-block mg-t-10">Forgot password?</a> --}}
            </div><!-- form-group -->
            <button class="btn btn-info btn-block" style="background-color: #FF7648; border: 1px solid #FF7648;">Sign In</button>
          </form>
        </div>

        {{-- <div class="mg-t-60 tx-center">Not yet a member? <a href="" class="tx-info">Sign Up</a></div> --}}
      </div><!-- login-wrapper -->
    </div><!-- col -->
    
  </div><!-- row -->
</div>
<div class="d-block d-lg-none">
  <div class="row no-gutters flex-row ht-100v">
    <div class="col-md-6 bg-gray-200 d-flex align-items-center justify-content-center">
      <div class="login-wrapper w-100 mg-y-30 mx-5">
        <div class="d-flex align-items-center justify-content-between">
          <h4 class="tx-inverse">Sign In</h4>
          <img src="{{ asset('image/logo_mobile.png') }}" style="width: 10rem;"/>
        </div>

        <div class="mt-3">
          @if(Session::has('message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ Session::get('message') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif

          <form method="post" action="{{ url('/auth/login') }}">
            @csrf
            <input type="hidden" name="type" value="web_admin"/>
            <div class="form-group">
              <input type="email" name="email" required class="form-control" placeholder="Enter your email">
            </div><!-- form-group -->
            <div class="form-group">
              <input type="password" name="password" required class="form-control" placeholder="Enter your password">
              {{-- <a href="" class="tx-info tx-12 d-block mg-t-10">Forgot password?</a> --}}
            </div><!-- form-group -->
            <button class="btn btn-info btn-block" style="background-color: #FF7648; border: 1px solid #FF7648;">Sign In</button>
          </form>
        </div>

        {{-- <div class="mg-t-60 tx-center">Not yet a member? <a href="" class="tx-info">Sign Up</a></div> --}}
      </div><!-- login-wrapper -->
    </div><!-- col -->
    
  </div><!-- row -->
</div>
@endsection
