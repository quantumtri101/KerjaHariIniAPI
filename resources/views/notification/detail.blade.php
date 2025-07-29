@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.notification'),
      __('notification.detail'),
    ],
    "title" => __('notification.detail'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => __('notification.detail'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">

      <div class="row">
        <div class="col-12">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>{{ __('general.id') }}</label>
                <input type="text" disabled name="id" class="form-control" value="{{ $notification->id }}"/>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label>{{ __('general.status') }}</label>
                <input type="text" disabled name="status" class="form-control" value="{{ $notification->status }}"/>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>{{ __('general.title') }}</label>
                <input type="text" disabled name="title" class="form-control" value="{{ $notification->title }}"/>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>{{ __('general.detail') }}</label>
                <textarea disabled name="detail" class="form-control">{{ strip_tags($notification->detail) }}</textarea>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label>{{ __('general.outlet') }}</label>
                <input type="text" disabled name="outlet_name" class="form-control" value="{{ $notification->outlet->name }}"/>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label>{{ __('general.scheduled_at') }}</label>
                <input type="text" disabled name="scheduled_at" class="form-control" value="{{ $notification->scheduled_at->formatLocalized('%d %B %Y %H:%M') }}"/>
              </div>
            </div>

            <div class="col-6">
              <div class="form-group">
                <label>{{ __('general.type') }}</label>
                <input type="text" disabled name="type_name" class="form-control" value="{{ $notification->type->name }}"/>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          @if($notification->status == "pending")
          <a class="btn btn-primary" onclick="save_current_page('{{ __('notification.detail') }}')" href="{{ url('/notification/action?id='.$notification->id) }}">{{ __('general.edit') }}</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('script')
    <script>
      $(document).ready(() => {

      })
    </script>
  @endpush
@endsection
