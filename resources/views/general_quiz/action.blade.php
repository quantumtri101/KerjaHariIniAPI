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

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/general-quiz/edit' : '/general-quiz') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.status_publish') }}</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="1" {{ !empty($general_quiz_question) && $general_quiz_question->is_publish == 1 ? 'checked' : '' }} id="radio-publish">
            <label class="form-check-label" for="radio-publish">
              {{ __('general.publish') }}
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="0" {{ !empty($general_quiz_question) && $general_quiz_question->is_publish == 0 ? 'checked' : '' }} id="radio-not_publish">
            <label class="form-check-label" for="radio-not_publish">
              {{ __('general.not_publish') }}
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>{{ __('general.name') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($general_quiz_question) ? $general_quiz_question->name : '' }}"/>
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
