@extends('layout.base_auth')

@section('content')
  <div class="d-flex justify-content-center align-items-center h-100" id="action">
    <div class="text-center my-5 w-75">
      <img src="{{ $url_asset.'/image/login.png' }}" style="width: 30rem"/>

      <div class="card o-hidden border-0 shadow-lg mt-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-12">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 m-0">{{ __('error_handler.link_expired') }}</h1>
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
          password: '',
          confirm_password: '',
          is_show_password: false,
          is_show_confirm_password: false,
        },
        methods: {
          on_submit(e){
            if(this.password !== this.confirm_password){
              e.preventDefault()
              notify_user('{{ __('error_handler.confirm_password_not_same') }}')
            }
          },
          on_show_password(){
            this.is_show_password = !this.is_show_password
          },
          on_show_confirm_password(){
            this.is_show_confirm_password = !this.is_show_confirm_password
          },
        },
      })
    </script>
  @endpush
@endsection
