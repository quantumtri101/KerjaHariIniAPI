@extends('layout.base_auth')

@section('content')
  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="text-center my-5 w-75">

      <div class="card o-hidden border-0 shadow-lg mt-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-12">
              <div class="p-3 p-lg-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">{{ __('general.login') }}</h1>
                </div>

                @if(Session::has('message'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif

                <form class="user text-left" method="post" action="{{ url('/auth/login') }}">
                  @csrf
                  <input type="hidden" name="type" value="web_admin"/>

                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                  </div>

                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                  </div>

                  <button class="btn btn-primary btn-user btn-block">{{ __('general.login') }}</button>
                </form>

                {{-- <div class="text-left mt-3">
                  <a href="{{ url('/auth/forget-password') }}" class="text-primary">{{ __('general.forget_password') }}</a>
                </div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
