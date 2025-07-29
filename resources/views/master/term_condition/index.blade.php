@extends('layout.base')

@section('content')
  <h5 class="m-0">{{ __('term_condition.edit') }}</h5>

  <div class="my-3">
    <form method="post" action="{{ url('/master/term-condition') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="card" id="term_condition">
        <div class="card-body">
          <div class="form-group d-none">
            <label>{{ __('general.title') }}</label>
            <input type="text" name="title" class="form-control" value="{{ !empty($term_condition) ? $term_condition->title : '' }}"/>
          </div>

          <div class="form-group">
            <label>{{ __('general.content') }}</label>
            {{-- <input type="hidden" name="description" v-model="description"/> --}}
            <textarea name="content" required id="summernote"></textarea>
          </div>
        </div>
      </div>

      <div class="mt-3">
        <button class="btn btn-primary" id="submit" disabled>{{ __('general.submit') }}</button>
      </div>

    </form>
  </div>

  @push('script')
    <script>
      $(document).ready(() => {
        $('#summernote').summernote({
          placeholder: '',
          height: 100,
          callbacks: {
            onKeydown: function(e) {
              $('#submit').removeAttr('disabled')
            }
          },
        });
        $("#summernote").summernote("code", `{!! $term_condition->content !!}`)
      })
    </script>
  @endpush
@endsection
