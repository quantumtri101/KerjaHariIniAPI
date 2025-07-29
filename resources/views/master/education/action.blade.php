@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.education'),
      __('education.edit'),
    ] : [
      __('general.education'),
      __('education.add'),
    ],
    "title" => Request::has('id') ? __('education.edit') : __('education.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('education.edit') : __('education.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/master/education/edit' : '/master/education') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.status_publish') }}</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="1" id="radio-publish" {{ !empty($education) && $education->is_publish == 1 ? 'checked' : '' }} required>
            <label class="form-check-label" for="radio-publish">
              {{ __('general.publish') }}
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="0" id="radio-not_publish" {{ !empty($education) && $education->is_publish == 0 ? 'checked' : '' }} required>
            <label class="form-check-label" for="radio-not_publish">
              {{ __('general.not_publish') }}
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>{{ __('general.name') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($education) ? $education->name : '' }}"/>
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
