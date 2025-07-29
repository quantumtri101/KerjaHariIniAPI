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

<form method="post" class="mt-3" action="{{ url('/master/sub-category/multiple') }}" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="id" value="{{ Request::get('id') }}"/>

  <div class="card mt-3">
    <div class="card-body">
      <div class="form-group">
        <label>{{ __('general.category') }}</label>
        <select name="category_id" class="form-control">
          @foreach($arr_category as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <input type="hidden" id="arr_sub_category" name="arr_sub_category"/>
      <div class="row">
        <div class="col-12 col-lg-4">
          @include('master.sub_category.component.multiple.add')
        </div>
        <div class="col-12 col-lg-8">
          @include('master.sub_category.component.multiple.table')
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
          if(arr_sub_category.length == 0){
            e.preventDefault()
            notify_user('{{ __("general.list_sub_category_empty") }}')
          }
          else
            back_page(false)
        })
      })
    </script>
  @endpush
@endsection
