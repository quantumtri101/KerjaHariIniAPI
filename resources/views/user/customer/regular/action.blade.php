@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.customer_regular'),
      __('customer_regular.detail'),
      __('customer_regular.edit'),
    ] : [
      __('general.customer_regular'),
      __('customer_regular.add'),
    ],
    "title" => Request::has('id') ? __('customer_regular.edit') : __('customer_regular.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('customer_regular.edit') : __('customer_regular.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/user/customer/regular/edit' : '/user/customer/regular') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="row">
          <div class="col-12 col-lg-4">
            <div class="mt-3">
              <label>{{ __('general.image') }}</label>
              @include('layout.upload_photo', [
                'id' => 'image',
                'data' => $user,
                'form_name' => 'image',
                'url_image' => '/image/user',
              ])
            </div>

            <div class="mt-3">
              <label>{{ __('general.covid_vaccine') }}</label>
              @include('layout.upload_photo', [
                'id' => 'vaccine_covid',
                'data' => $user,
                'form_name' => 'vaccine_covid_image',
                'column' => 'vaccine_covid_file_name',
                'url_image' => '/image/user/vaccine',
              ])
            </div>

            <div class="mt-3">
              <label>{{ __('general.cv') }}</label>
              @include('layout.upload_photo', [
                'id' => 'cv',
                'data' => $user,
                'form_name' => 'cv_image',
                'column' => 'cv_file_name',
                'url_image' => '/image/user/cv',
              ])
            </div>
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
              <select required name="company_id" {{ !empty(Auth::user()->company) ? 'disabled' : '' }} class="form-control">
                <option value="">{{ __('general.choose_company') }}</option>
                @foreach($arr_company as $company)
                  <option value="{{ $company->id }}" {{ (!empty(Auth::user()->company) && Auth::user()->company->id == $company->id) || (empty(Auth::user()->company) && !empty($user) && !empty($user->company) && $user->company->id == $company->id) ? 'selected' : '' }}>{{ $company->name }}</option>
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

            <div class="form-group">
              <label>{{ __('general.contract_start_date') }}</label>
              <input type="text" required name="contract_start_date" value="{{ !empty($user) ? $user->contract_start_date->formatLocalized('%d/%m/%Y') : '' }}" onkeydown="return false" id="contract_start_date" class="form-control datetimepicker-input" data-toggle="datetimepicker"/>
            </div>

            <div class="form-group">
              <label>{{ __('general.contract_duration') }}</label>
              <div class="input-group">
                <input type="text" required name="contract_duration" id="contract_duration" class="form-control" value="{{ !empty($user) ? number_format($user->contract_duration, 0, ',', '.') : '' }}"/>
                <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon1">{{ __('general.year') }}</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>{{ __('general.id_no') }}</label>
              <input type="text" required name="id_no" id="id_no" class="form-control" value="{{ !empty($user) ? $user->id_no : '' }}"/>
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
        $('#contract_start_date').datetimepicker({
          format: 'DD/MM/YYYY',
          useCurrent: false,
          defaultDate: $('#contract_start_date').val() != '' ? moment($('#contract_start_date').val(), 'DD/MM/YYYY') : moment(),
          // minDate: moment(),
        })

        $('#contract_duration').keyup(() => {
          $('#contract_duration').val(phone_validation($('#contract_duration').val(), 2))
        })

        $('#phone').keyup(() => {
          $('#phone').val(phone_validation($('#phone').val()))
        })

        $('#id_no').keyup(() => {
          $('#id_no').val(phone_validation($('#id_no').val(), 16))
        })

        // init_birth_date()
      })
    </script>
  @endpush
@endsection
