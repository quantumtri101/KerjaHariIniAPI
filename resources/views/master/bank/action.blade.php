@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.bank'),
      __('bank.edit'),
    ] : [
      __('general.bank'),
      __('bank.add'),
    ],
    "title" => Request::has('id') ? __('bank.edit') : __('bank.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('bank.edit') : __('bank.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/master/bank/edit' : '/master/bank') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.name') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($bank) ? $bank->name : '' }}"/>
        </div>

        <div class="form-group">
          <label>{{ __('general.image') }}</label>
          @include('layout.upload_photo', [
            "column" => "file_name",
            "form_name" => "image",
            "data" => $bank,
            "id" => "bank_image",
            "url_image" => "/image/bank",
          ])
        </div>

        <div class="form-group" >
          <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
          <button class="btn btn-primary" id="submit">{{ __('general.submit') }}</button>
        </div>
      </form>
    </div>
  </div>

  @push('script')
    <script>
      $(document).ready(() => {
        $('#submit').click((e) => {
          
        })
      })
    </script>
  @endpush
@endsection
