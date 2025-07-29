@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.general_quiz'),
      __('general_quiz.edit'),
    ] : [
      __('general.general_quiz'),
      __('general_quiz.add'),
    ],
    "title" => Request::has('id') ? __('general_quiz.edit') : __('general_quiz.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('general_quiz.edit') : __('general_quiz.add'),
  ])

<form method="post" class="mt-3" action="{{ url('/general-quiz/multiple') }}" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="id" value="{{ Request::get('id') }}"/>

  <div class="card mt-3">
    <div class="card-body">
      <input type="hidden" id="arr_general_quiz" name="arr_general_quiz"/>
      <div class="row">
        <div class="col-12 col-lg-4">
          @include('general_quiz.component.multiple.add')
        </div>
        <div class="col-12 col-lg-8">
          @include('general_quiz.component.multiple.table')
        </div>
      </div>
    </div>
  </div>

  <div class="form-group mt-3" >
    <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
    <button class="btn btn-primary" id="submit" onclick="back_page(false)">{{ __('general.submit') }}</button>
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
