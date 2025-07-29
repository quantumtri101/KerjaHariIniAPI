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

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/master/range-salary/edit' : '/master/range-salary') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.status_publish') }}</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="1" id="radio-publish" {{ !empty($range_salary) && $range_salary->is_publish == 1 ? 'checked' : '' }} required>
            <label class="form-check-label" for="radio-publish">
              {{ __('general.publish') }}
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_publish" value="0" id="radio-not_publish" {{ !empty($range_salary) && $range_salary->is_publish == 0 ? 'checked' : '' }} required>
            <label class="form-check-label" for="radio-not_publish">
              {{ __('general.not_publish') }}
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>{{ __('general.min_salary') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
            </div>
            <input type="text" required name="min_salary" id="min_salary" class="form-control" value="{{ !empty($range_salary) ? number_format($range_salary->min_salary, 0, ',', '.') : '' }}"/>
          </div>
        </div>
      
        <div class="form-group">
          <label>{{ __('general.max_salary') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
            </div>
            <input type="text" required name="max_salary" id="max_salary" class="form-control" value="{{ !empty($range_salary) ? number_format($range_salary->max_salary, 0, ',', '.') : '' }}"/>
          </div>
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

        $('#min_salary').keyup(() => {
          var min_salary = str_to_double($('#min_salary').val())
          var max_salary = str_to_double($('#max_salary').val())

          if(min_salary > max_salary && max_salary > 0)
            $('#min_salary').val(to_currency_format($('#max_salary').val()))
          else
            $('#min_salary').val(to_currency_format($('#min_salary').val()))
        })

        $('#max_salary').keyup(() => {
          var min_salary = str_to_double($('#min_salary').val())
          var max_salary = str_to_double($('#max_salary').val())

          if(max_salary < min_salary && min_salary > 0)
            $('#max_salary').val(to_currency_format($('#min_salary').val()))
          else
            $('#max_salary').val(to_currency_format($('#max_salary').val()))
        })
      })
    </script>
  @endpush
@endsection
