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
        <input class="form-check-input" type="radio" name="is_active" value="1" id="radio-active" required {{ !empty($user) && $user->is_active == '1' ? 'checked' : '' }}>
        <label class="form-check-label" for="radio-active">
          {{ __('general.active') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_active" value="0" id="radio-inactive" required {{ !empty($user) && $user->is_active == '0' ? 'checked' : '' }}>
        <label class="form-check-label" for="radio-inactive">
          {{ __('general.inactive') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.name') }}</label>
      <input type="text" required name="name" id="name" class="form-control" value="{{ !empty($user) ? $user->name : '' }}"/>
    </div>

    <div class="form-group">
      <label>{{ __('general.email') }}</label>
      <input type="email" required name="email" id="email" class="form-control" value="{{ !empty($user) ? $user->email : '' }}"/>
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
      <select required name="gender" id="gender" class="form-control">
        <option value="1" {{ !empty($user) && $user->gender == 1 ? 'selected' : '' }}>{{ __('general.male') }}</option>
        <option value="0" {{ !empty($user) && $user->gender == 0 ? 'selected' : '' }}>{{ __('general.female') }}</option>
      </select>
    </div>

    {{-- <div class="form-group">
      <label>{{ __('general.birth_date') }}</label>
      <input type="text" required name="birth_date" onkeydown="return false" id="birth_date" class="form-control datetimepicker-input" data-toggle="datetimepicker"/>
    </div> --}}
  </div>
</div>

@push('script')
  <script>
    function check_general(){
      var message = ""
      
      if(!$('#radio-active').is(':checked') && !$('#radio-inactive').is(':checked'))
        message = "{{ __('general.status_active_not_choosen') }}"
      else if($('#name').val() == "")
        message = "{{ __('general.name_empty') }}"
      else if($('#email').val() == "")
        message = "{{ __('general.email_empty') }}"
      else if($('#phone').val() == "")
        message = "{{ __('general.phone_empty') }}"
      else if($('#gender').val() == "")
        message = "{{ __('general.gender_not_choosen') }}"
      else if(!validate_email($('#email').val()))
        message = "{{ __('general.not_email_format') }}"
      return message
    }
  </script>
@endpush