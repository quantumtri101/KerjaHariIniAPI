@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.sub_category'),
      __('sub_category.edit'),
    ] : [
      __('general.sub_category'),
      __('sub_category.add'),
    ],
    "title" => Request::has('id') ? __('sub_category.edit') : __('sub_category.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('sub_category.edit') : __('sub_category.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/master/sub-category/edit' : '/master/sub-category') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.image') }}</label>
          @include('layout.upload_photo', [
            "column" => "file_name",
            "form_name" => "image",
            "data" => $sub_category,
            "id" => "image",
            "url_image" => "/image/sub-category",
          ])
        </div>

        <div class="form-group">
          <label>{{ __('general.category') }}</label>
          <select name="category_id" required class="form-control">
            @foreach($arr_category as $category)
              <option value="{{ $category->id }}" {{ !empty($sub_category) && $sub_category->category->id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>{{ __('general.name') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($sub_category) ? $sub_category->name : '' }}"/>
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
