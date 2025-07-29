@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.ro'),
      __('ro.detail'),
      __('ro.edit'),
    ] : [
      __('general.ro'),
      __('ro.add'),
    ],
    "title" => Request::has('id') ? __('ro.edit') : __('ro.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('ro.edit') : __('ro.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/user/ro/edit' : '/user/ro') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="row">
          <div class="col-12 col-lg-4">
            @include('layout.upload_photo', [
              'data' => $user,
              'url_image' => '/image/user',
            ])
          </div>

          <div class="col-12 col-lg-8">
            <div class="form-group">
              <label>{{ __('general.status_active') }}</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_active" value="1" id="defaultCheckActive" required {{ !empty($user) && $user->is_active == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="defaultCheckActive">
                  {{ __('general.active') }}
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_active" value="0" id="defaultCheckInactive" required {{ !empty($user) && $user->is_active == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="defaultCheckInactive">
                  {{ __('general.inactive') }}
                </label>
              </div>
            </div>

            <div class="form-group">
              <label>{{ __('general.company') }}</label>
              <select required name="company_id" class="form-control">
                <option value="">{{ __('general.choose_company') }}</option>
                @foreach($arr_company as $company)
                  <option value="{{ $company->id }}" {{ !empty($user) && $user->company->id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>{{ __('general.name') }}</label>
              <input type="text" required name="name" class="form-control" value="{{ !empty($user) ? $user->name : '' }}"/>
            </div>

            <div class="form-group">
              <label>{{ __('general.email') }}</label>
              <input type="email" required name="email" class="form-control" value="{{ !empty($user) ? $user->email : '' }}"/>
            </div>

            <div class="form-group">
              <label>{{ __('general.phone') }}</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
                </div>
                <input type="text" required name="phone" id="phone" class="form-control" value="{{ !empty($user) ? $user->phone : '' }}"/>
              </div>
            </div>

            <div class="form-group">
              <label>{{ __('general.gender') }}</label>
              <select required name="gender" class="form-control">
                <option value="1" {{ !empty($user) && $user->gender == 1 ? 'selected' : '' }}>{{ __('general.male') }}</option>
                <option value="0" {{ !empty($user) && $user->gender == 0 ? 'selected' : '' }}>{{ __('general.female') }}</option>
              </select>
            </div>

            {{-- <div class="form-group">
              <label>{{ __('general.birth_date') }}</label>
              <input type="text" required name="birth_date" onkeydown="return false" id="birth_date" class="form-control datetimepicker-input" data-toggle="datetimepicker"/>
            </div> --}}
          </div>

          <div class="col-12 mt-3">
            <div class="form-group" >
              <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
              <button class="btn btn-primary" id="submit" onclick="back_page(false)">{{ __('general.submit') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('script')
    <script>
      // function init_birth_date(){
      //   $('#birth_date').datetimepicker('destroy')
      //   $('#birth_date').datetimepicker({
      //     format: 'DD/MM/YYYY',
      //     useCurrent: false,
      //     defaultDate: moment(birth_date, 'DD/MM/YYYY'),
      //     maxDate: moment().subtract(13, 'y'),
      //   })

      //   $('#birth_date').on("change.datetimepicker", ({date, oldDate}) => {
      //     if(oldDate != null){
      //       birth_date = $('#birth_date').val()
      //     }
      //   })
      // }

      $(document).ready(() => {
        $('#phone').keyup(() => {
          $('#phone').val(phone_validation($('#phone').val()))
        })

        // init_birth_date()
      })
    </script>
  @endpush
@endsection
