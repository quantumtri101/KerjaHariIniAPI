<div class="row">
  <div class="col-12 col-lg-4">
    <div>
      <label>{{ __('general.vaccine_covid') }}</label>
      @include('layout.upload_photo', [
        "column" => "vaccine_covid_file_name",
        "form_name" => "vaccine_covid_image",
        "data" => $user,
        "id" => "vaccine_covid_image",
        "url_image" => "/image/user/vaccine-covid",
      ])
    </div>

    <div class="mt-3">
      <label>{{ __('general.cv') }}</label>
      @include('layout.upload_photo', [
        "column" => "cv_file_name",
        "form_name" => "cv_image",
        "data" => $user,
        "id" => "cv_image",
        "url_image" => "/image/user/cv",
      ])
    </div>
  </div>
  <div class="col-12 col-lg-8">
    <div class="form-group">
      <label>{{ __('general.birth_date') }}</label>
      <input type="text" required name="birth_date" id="birthdatetimepicker" class="form-control" value="{{ !empty($user) && count($user->resume) > 0 ? $user->birth_date : '' }}" data-toggle="datetimepicker" data-target="#birthdatetimepicker"/>
    </div>

    <div class="form-group">
      <label>{{ __('general.address') }}</label>
      <textarea required name="address" id="address" class="form-control">{{ !empty($user) && count($user->resume) > 0 ? $user->resume[0]->address : '' }}</textarea>
    </div>

    <div class="form-group">
      <label>{{ __('general.city') }}</label>
      <select required name="city_id" class="form-control" id="city"></select>
    </div>

    <div class="form-group">
      <label>{{ __('general.marital_status') }}</label>
      <select required name="marital_status" class="form-control" id="marital_status">
        <option value="">{{ __('general.choose_marital_status') }}</option>
        @foreach($arr_marital_status as $marital_status)
          <option value="{{ $marital_status['id'] }}" {{ !empty($user) && count($user->resume) > 0 && $marital_status['id'] == $user->resume[0]->marital_status ? 'selected' : '' }}>{{ __('general.'.$marital_status['id']) }}</option>
        @endforeach
      </select>
    </div>

    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label>{{ __('general.height') }}</label>
          <div class="input-group mb-3">
            <input type="text" required name="height" id="height" class="form-control" value="{{ !empty($user) && count($user->resume) > 0 ? $user->resume[0]->height : '' }}"/>
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon1">cm</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label>{{ __('general.weight') }}</label>
          <div class="input-group mb-3">
            <input type="text" required name="weight" id="weight" class="form-control" value="{{ !empty($user) && count($user->resume) > 0 ? $user->resume[0]->weight : '' }}"/>
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon1">kg</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.education') }}</label>
      <select required name="education_id" class="form-control" id="education"></select>
    </div>

    <div class="form-group">
      <label>{{ __('general.bank') }}</label>
      <select required name="bank_id" class="form-control" id="bank"></select>
    </div>

    <div class="form-group">
      <label>{{ __('general.acc_no') }}</label>
      <input type="text" required name="acc_no" class="form-control" value="{{ !empty($user) && count($user->resume) > 0 ? $user->resume[0]->acc_no : '' }}"/>
    </div>
  </div>
</div>

@push('script')
  <script>
    function check_resume(){
      var message = ""
      
      if(!$('#radio-active').is(':checked') && !$('#radio-inactive').is(':checked'))
        message = "{{ __('general.status_active_not_choosen') }}"
      else if($('#birthdatetimepicker').val() == "")
        message = "{{ __('general.birth_date_empty') }}"
      else if($('#address').val() == "")
        message = "{{ __('general.address_empty') }}"
      else if($('#city').val() == "")
        message = "{{ __('general.city_not_choosen') }}"
      else if($('#marital_status').val() == "")
        message = "{{ __('general.marital_status_not_choosen') }}"
      else if($('#height').val() == "" || $('#height').val() == "0")
        message = "{{ __('general.height_empty') }}"
      else if($('#weight').val() == "" || $('#weight').val() == "0")
        message = "{{ __('general.weight_empty') }}"
      else if($('#education').val() == "")
        message = "{{ __('general.education_not_choosen') }}"
      else if($('#bank').val() == "")
        message = "{{ __('general.bank_not_choosen') }}"
      else if($('#acc_no').val() == "")
        message = "{{ __('general.acc_no_empty') }}"
      return message
    }
  </script>
@endpush

@push('afterScript')
$('#height').keyup(() => {
  $('#height').val(to_currency_format($('#height').val()))
})
$('#width').keyup(() => {
  $('#width').val(to_currency_format($('#width').val()))
})
$('#birthdatetimepicker').datetimepicker({
  format: 'DD-MM-YYYY',
  maxDate: moment().subtract(20, 'years'),
})
$('#city').select2({
  ajax: {
    url: '{{ url('/api/city/all') }}',
    dataType: 'json',
    processResults: function (data) {
      // Transforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data
      };
    }
  },
})
$('#education').select2({
  ajax: {
    url: '{{ url('/api/education/all') }}',
    dataType: 'json',
    processResults: function (data) {
      // Transforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data
      };
    }
  },
})
$('#bank').select2({
  ajax: {
    url: '{{ url('/api/bank/all') }}',
    dataType: 'json',
    processResults: function (data) {
      // Transforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data.data
      };
    }
  },
})

@if(!empty($user) && count($user->resume) > 0)
  $('#city').html(`<option value="{{ $user->resume[0]->city->id }}" selected>{{ $user->resume[0]->city->name }}</option>`)
  $('#education').html(`<option value="{{ $user->resume[0]->education->id }}" selected>{{ $user->resume[0]->education->name }}</option>`)
  $('#bank').html(`<option value="{{ $user->resume[0]->bank->id }}" selected>{{ $user->resume[0]->bank->name }}</option>`)
@endif
@endpush