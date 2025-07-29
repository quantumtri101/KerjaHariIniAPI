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
                  <h1 class="h4 text-gray-900 mb-4">{{ __('general.insert_new_password') }}</h1>
                </div>

                <form class="user text-left" method="post" action="{{ url('/auth/reset-password') }}">
                  @csrf
                  <input type="hidden" name="id" value="{{ $user->id }}"/>

                  <div class="form-group">
                    <label>{{ __('general.password') }}</label>
                    <div class="form-control form-control-user d-flex px-3 py-1 h-auto justify-content-between align-items-center">
                      <input :type="!is_show_password ? 'password' : 'text'" name="password" class="w-100 mr-3" id="exampleInputPassword" v-model="password" placeholder="{{ __('general.password') }}" style="border: none; height: 3rem">

                      <i class="fa-solid " :class="{'fa-eye': is_show_password, 'fa-eye-slash': !is_show_password,}" @click="on_show_password" style="font-size: 1.3rem"></i>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>{{ __('general.retype_password') }}</label>
                    <div class="form-control form-control-user d-flex px-3 py-1 h-auto justify-content-between align-items-center">
                      <input :type="!is_show_confirm_password ? 'password' : 'text'" name="confirm_password" class="w-100 mr-3" id="exampleInputPassword" v-model="confirm_password" placeholder="{{ __('general.retype_password') }}" style="border: none; height: 3rem">

                      <i class="fa-solid " :class="{'fa-eye': is_show_confirm_password, 'fa-eye-slash': !is_show_confirm_password,}" @click="on_show_confirm_password" style="font-size: 1.3rem"></i>
                    </div>
                  </div>

                  <button class="btn btn-primary btn-user btn-block" @click="on_submit">{{ __('general.submit') }}</button>
                </form>
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
