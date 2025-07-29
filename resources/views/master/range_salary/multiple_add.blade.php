@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.range_salary'),
      __('range_salary.edit'),
    ] : [
      __('general.range_salary'),
      __('range_salary.add'),
    ],
    "title" => Request::has('id') ? __('range_salary.edit') : __('range_salary.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('range_salary.edit') : __('range_salary.add'),
  ])

<form method="post" class="mt-3" action="{{ url('/master/range-salary/multiple') }}" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="id" value="{{ Request::get('id') }}"/>

  <div class="card mt-3">
    <div class="card-body">
      <input type="hidden" id="arr_range_salary" name="arr_range_salary"/>
      <div class="row">
        <div class="col-12 col-lg-4">
          @include('master.range_salary.component.multiple.add')
        </div>
        <div class="col-12 col-lg-8">
          @include('master.range_salary.component.multiple.table')
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
          if(arr_range_salary.length == 0){
            e.preventDefault()
            notify_user('{{ __("general.list_range_salary_empty") }}')
          }
          else
            back_page(false)
        })
      })
    </script>
  @endpush
@endsection
