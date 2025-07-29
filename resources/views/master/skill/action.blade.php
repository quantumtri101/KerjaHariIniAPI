@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.skill'),
      __('skill.edit'),
    ] : [
      __('general.skill'),
      __('skill.add'),
    ],
    "title" => Request::has('id') ? __('skill.edit') : __('skill.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('skill.edit') : __('skill.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/master/skill/edit' : '/master/skill') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.skill') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($skill) ? $skill->name : '' }}"/>
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
