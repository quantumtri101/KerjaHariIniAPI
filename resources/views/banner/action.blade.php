@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.banner'),
      // __('banner.detail'),
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

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/banner/edit' : '/banner') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif
        <input type="hidden" name="type" value="banner"/>

        <div class="form-group">
          <label>{{ __('general.status_publish') }}</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="1" {{ !empty($banner) && $banner->is_publish == 1 ? 'checked' : '' }} id="radio-publish" required>
            <label class="form-check-label" for="radio-publish">
              {{ __('general.publish') }}
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="0" {{ !empty($banner) && $banner->is_publish == 0 ? 'checked' : '' }} id="radio-not_publish" required>
            <label class="form-check-label" for="radio-not_publish">
              {{ __('general.not_publish') }}
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>{{ __('general.image') }}</label>
          @include('layout.upload_photo', [
            "column" => "file_name",
            "form_name" => "image",
            "data" => $banner,
            "id" => "banner_image",
            "url_image" => "/image/banner",
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

        $('#price').keyup(() => {
          $('#price').val(to_currency_format($('#price').val()))
        })

        $('#duration').keyup(() => {
          $('#duration').val(to_currency_format($('#duration').val()))
        })

        $('#category').change(() => {
          $('#sub_category_container').removeClass('d-none')
          this.get_sub_category($('#category').val())
        })
      })
    </script>
  @endpush
@endsection
