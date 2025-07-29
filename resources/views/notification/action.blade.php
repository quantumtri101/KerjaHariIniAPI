@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.notification'),
      __('notification.detail'),
      __('notification.edit'),
    ] : [
      __('general.notification'),
      __('notification.add'),
    ],
    "title" => Request::has('id') ? __('notification.edit') : __('notification.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('notification.edit') : __('notification.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/notification/edit' : '/notification') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>{{ __('general.outlet') }}</label>
                  <select class="outlet-ajax form-control" name="outlet_id">
                  </select>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label>{{ __('general.type') }}</label>
                  <select class="type-ajax form-control" name="type_id"></select>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label>{{ __('general.title') }}</label>
                  <input type="text" required name="title" id="title" class="form-control" value="{{ !empty($notification) ? $notification->title : '' }}"/>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label>{{ __('general.detail') }}</label>
                  <textarea required name="detail" id="detail" class="form-control">{{ !empty($notification) ? $notification->detail : '' }}</textarea>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label>{{ __('general.scheduled_at') }}</label>
                  <input type="text" required name="scheduled_at" id="scheduled_at" class="form-control datetimepicker-input" onkeydown="return false;" data-toggle="datetimepicker"/>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 mt-3">
            <div class="form-group" >
              <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
              <button class="btn btn-primary" id="submit">{{ __('general.submit') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('script')
    <script>
      var scheduled_at = '{{ !empty($notification) ? $notification->scheduled_at->formatLocalized("%d/%m/%Y %H:%M") : Carbon\Carbon::now()->formatLocalized("%d/%m/%Y %H:%M") }}'

      function init_scheduled_at(){
        $('#scheduled_at').datetimepicker('destroy')
        $('#scheduled_at').datetimepicker({
          format: 'DD/MM/YYYY HH:mm',
          defaultDate: {{ !empty($notification) ? 1 : 0 }} == 1 ? moment(scheduled_at, 'DD/MM/YYYY HH:mm') : moment(),
          minDate: moment(),
          icons: {
            time: "fa-solid fa-clock",
            date: "fa-solid fa-calendar",
          },
        })

        $('#scheduled_at').on("change.datetimepicker", ({date, oldDate}) => {
          if(oldDate != null){
            scheduled_at = $('#scheduled_at').val()
          }
        })
      }

      $(document).ready(() => {
        $('#detail').summernote()

        init_scheduled_at()
        
        @if(!empty($notification))
          $('.outlet-ajax').html(`<option value="{{ $notification->outlet->id }}" selected>{{ $notification->outlet->name }}</option>`)
          $('.type-ajax').html(`<option value="{{ $notification->type->id }}" selected>{{ $notification->type->name }}</option>`)
        @endif

        $('.outlet-ajax').select2({
          ajax: {
            url: '{{ url("api/outlet") }}',
            dataType: 'json',
            accept: 'application/json',
            data: function (params) {
              var query = {
                search: params.term
              }

              return query;
            },
            processResults: function (data) {
              return {
                results: data.data
              };
            }
          }
        });

        $('.type-ajax').select2({
          ajax: {
            url: '{{ url("api/type/all") }}',
            dataType: 'json',
            accept: 'application/json',
            data: function (params) {
              var query = {
                search: params.term
              }

              return query;
            },
            processResults: function (data) {
              return {
                results: data.data
              };
            }
          }
        });
      })
    </script>
  @endpush
@endsection
