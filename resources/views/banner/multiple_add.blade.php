@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.banner'),
      __('banner.detail'),
      __('banner.edit'),
    ] : [
      __('general.banner'),
      __('banner.add'),
    ],
    "title" => Request::has('id') ? __('banner.edit') : __('banner.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('banner.edit') : __('banner.add'),
  ])

<form method="post" class="mt-3" action="{{ url('/banner/multiple') }}" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
  <input type="hidden" name="type" value="banner"/>

  <div class="card mt-3">
    <div class="card-body">
      <input type="hidden" id="arr_banner" name="arr_banner"/>
      <div class="row">
        <div class="col-12 col-lg-4">
          @include('banner.component.banner.add')
        </div>
        <div class="col-12 col-lg-8">
          @include('banner.component.banner.table')
        </div>
      </div>
    </div>
  </div>

  <div class="form-group mt-3" >
    <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
    <button class="btn btn-primary" id="submit">{{ __('general.submit') }}</button>
  </div>
</form>

  @push('script')
    <script>
      $(document).ready(() => {
        $('#submit').click((e) => {
          
        })
      })
    </script>
  @endpush
@endsection
