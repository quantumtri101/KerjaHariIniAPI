@extends('layout.base')

@section('content')
  <div>
    <h3>{{ __('general.change_password') }}</h3>
    <form method="post" action="{{ url('/auth/change-password') }}">
      @csrf
      <div class="row">
        <div class="col-12 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label>{{ __('general.current_password') }}</label>
                <input type="password" class="form-control" required name="old_password"/>
              </div>
              <div class="form-group">
                <label>{{ __('general.new_password') }}</label>
                <input type="password" class="form-control" required id="new_password" name="new_password"/>
              </div>
              <div class="form-group">
                <label>{{ __('general.confirm_password') }}</label>
                <input type="password" class="form-control" required id="confirm_password" name="confirm_password"/>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mt-3">
          <button class="btn btn-primary" id="submit">{{ __('general.submit') }}</button>
        </div>
      </div>
    </form>
  </div>

  @push('script')
    <script>
      $(document).ready(async() => {
        $('#submit').click((e) => {
          if($('#new_password').val() !== $('#confirm_password').val()){
            e.preventDefault()
            alert('{{ __("error_handler.confirm_password_not_same") }}')
          }
        })
      })
    </script>
  @endpush
@endsection
