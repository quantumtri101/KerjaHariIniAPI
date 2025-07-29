@extends('layout.base_auth')

@section('content')
  <div class="d-flex justify-content-center align-items-center h-100" id="action">
    <div class="text-center my-5 w-75">
      <img src="{{ $url_asset.'/image/logo_admin.png' }}" style="width: 30rem"/>

      <div class="card o-hidden border-0 shadow-lg mt-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-12">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">{{ __('general.forget_password') }}</h1>
                </div>

                @if(Session::has('message'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif

                <form class="user text-left" method="post" action="{{ url('/auth/forget-password') }}">
                  @csrf

                  <input type="hidden" name="type" value="admin_operator"/>
                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                  </div>

                  <button class="btn btn-primary btn-user btn-block" @click="on_submit">{{ __('general.submit') }}</button>
                </form>

                <div class="text-left mt-3">
                  <a href="{{ url('/auth/login') }}">{{ __('general.login') }}</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('script')
    <script>
      var action = new Vue({
        el: '#action',
        data: {
          email: '',
        },
        methods: {
        },
      })
    </script>
  @endpush
@endsection
