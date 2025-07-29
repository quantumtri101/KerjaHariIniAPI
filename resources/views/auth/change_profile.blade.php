@extends('layout.base')

@section('content')
  <div>
    <h3>{{ __('general.edit_profile') }}</h3>
    <form method="post" action="{{ url('/auth/change-profile') }}" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-12 col-lg-4 mb-3">
          @include('layout.upload_photo',[
            'data' => Auth::user(),
            'url_image' => '/image/user',
          ])
        </div>
        <div class="col-12 col-lg-8 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label>{{ __('general.email') }}</label>
                <input type="text" class="form-control" name="email" value="{{ Auth::user()->email }}"/>
              </div>
              <div class="form-group">
                <label>{{ __('general.name') }}</label>
                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}"/>
              </div>
              <div class="form-group">
                <label>{{ __('general.phone') }}</label>
                <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}"/>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mt-3">
          <button class="btn btn-primary">{{ __('general.submit') }}</button>
        </div>
      </div>
    </form>
  </div>
@endsection
