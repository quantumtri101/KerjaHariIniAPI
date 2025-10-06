<div class="row">
  <div class="col-12">
    <div class="form-group">
      <label>{{ __('general.need_interview') }}</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="need_interview" value="1" {{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? 'checked' : '' }} id="radio-interview-yes">
        <label class="form-check-label" for="radio-interview-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="need_interview" value="0" {{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) == 0 ? 'checked' : 'checked' }} id="radio-interview-no">
        <label class="form-check-label" for="radio-interview-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div id="interview-layout" class="{{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? '' : 'd-none' }}">
      <div class="form-group">
        <label>{{ __('general.interview_type') }}</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="interview_type" value="offline" {{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 && $jobs->interview[0]->type == 'offline' ? 'checked' : '' }} id="radio-offline">
          <label class="form-check-label" for="radio-offline">
            {{ __('general.offline') }}
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="interview_type" value="online" {{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 && $jobs->interview[0]->type == 'online' ? 'checked' : '' }} id="radio-online">
          <label class="form-check-label" for="radio-online">
            {{ __('general.online') }}
          </label>
        </div>
      </div>

      <div class="form-group">
        <label>{{ __('general.interviewer_name') }}</label>
        <input type="text" name="interviewer_name" id="interviewer_name" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? $jobs->interview[0]->interviewer_name : '' }}"/>
      </div>

      <div class="form-group">
        <label>{{ __('general.interviewer_phone') }}</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
          </div>
          <input type="text" name="interviewer_phone" id="interviewer_phone" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? substr($jobs->interview[0]->interviewer_phone, 3) : '' }}"/>
        </div>
      </div>

      <div class="form-group {{ !empty($jobs) && count($jobs->interview) > 0 && $jobs->interview[0]->type == 'online' ? '' : 'd-none' }}" id="online_type">
        <label>{{ __('general.link_interview') }}</label>
        <input type="text" name="interview_link" id="interview_link" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? $jobs->interview[0]->zoom_url : '' }}"/>
      </div>

      <div class="form-group {{ !empty($jobs) && count($jobs->interview) > 0 && $jobs->interview[0]->type == 'offline' ? '' : 'd-none' }}" id="offline_type">
        <label>{{ __('general.location_interview') }}</label>
        <input type="text" name="interview_location" id="interview_location" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? $jobs->interview[0]->location : '' }}"/>
      </div>

      <div class="form-group">
        <label>{{ __('general.date_interview') }}</label>
        <input type="text" name="interview_date" id="interview_date" data-toggle="datetimepicker" data-target="#interview_date" class="form-control datetimepicker-input"/>
      </div>

      <div class="form-group">
        <label>{{ __('general.note_interview') }}</label>
        <textarea name="interview_notes" id="interview_notes" class="form-control">{{ !empty($jobs) && !$jobs->already_working && count($jobs->interview) > 0 ? $jobs->interview[0]->note : '' }}</textarea>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.need_briefing') }}</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="need_briefing" value="1" {{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) > 0 ? 'checked' : '' }} id="radio-briefing-yes">
        <label class="form-check-label" for="radio-briefing-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="need_briefing" value="0" {{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) == 0 ? 'checked' : 'checked' }} id="radio-briefing-no">
        <label class="form-check-label" for="radio-briefing-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div id="briefing-layout" class="{{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) > 0 ? '' : 'd-none' }}">
      <div class="form-group">
        <label>{{ __('general.person_in_charge_name') }}</label>
        <input type="text" name="pic_name" id="person_in_charge_name" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) > 0 ? $jobs->briefing[0]->pic_name : '' }}"/>
      </div>

      <div class="form-group">
        <label>{{ __('general.person_in_charge_phone') }}</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
          </div>
          <input type="text" name="pic_phone" id="person_in_charge_phone" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) > 0 ? substr($jobs->briefing[0]->pic_phone, 3) : '' }}"/>
        </div>
      </div>

      <div class="form-group">
        <label>{{ __('general.location_briefing') }}</label>
        <input type="text" name="briefing_location" id="location_briefing" class="form-control" value="{{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) > 0 ? $jobs->briefing[0]->location : '' }}"/>
      </div>

      <div class="form-group">
        <label>{{ __('general.date_briefing') }}</label>
        <input type="text" name="briefing_date" id="date_briefing" data-toggle="datetimepicker" data-target="#date_briefing" class="form-control datetimepicker-input"/>
      </div>

      <div class="form-group">
        <label>{{ __('general.note_briefing') }}</label>
        <textarea name="briefing_notes" id="briefing_notes" class="form-control">{{ !empty($jobs) && !$jobs->already_working && count($jobs->briefing) > 0 ? $jobs->briefing[0]->note : '' }}</textarea>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    {{-- <a class="btn btn-primary" onclick="save_current_page('{{ __('jobs.detail') }}')" href="{{ url('/jobs/action?id='.$jobs->id) }}">{{ __('general.cancel') }}</a>

    <a class="btn btn-primary ml-3" target="_blank" href="{{ url('/jobs/print-qr?id='.$jobs->id) }}">{{ __('general.next') }}</a> --}}
  </div>
</div>

@push('script')
  <script>
    function check_brief_interview(){
      var message = ""
      if($('#radio-interview-yes').is(':checked') && $('#interviewer_name').val() == "")
        message = "{{ __('general.interviewer_name_empty') }}"
      else if($('#radio-interview-yes').is(':checked') && $('#interviewer_phone').val() == "")
        message = "{{ __('general.interviewer_phone_empty') }}"
      else if($('#radio-interview-yes').is(':checked') && (!$('#radio-offline').is(':checked') && !$('#radio-online').is(':checked')))
        message = "{{ __('general.interview_type_not_choosen') }}"
      else if($('#radio-interview-yes').is(':checked') && $('#radio-offline').is(':checked') && $('#interview_location').val() == "")
        message = "{{ __('general.interview_location_empty') }}"
      else if($('#radio-interview-yes').is(':checked') && $('#radio-online').is(':checked') && $('#interview_link').val() == "")
        message = "{{ __('general.interview_link_empty') }}"
      else if($('#radio-interview-yes').is(':checked') && $('#interview_date').val() == "")
        message = "{{ __('general.interview_date_empty') }}"
      else if($('#radio-briefing-yes').is(':checked') && $('#person_in_charge_name').val() == "")
        message = "{{ __('general.person_in_charge_name_empty') }}"
      else if($('#radio-briefing-yes').is(':checked') && $('#person_in_charge_phone').val() == "")
        message = "{{ __('general.person_in_charge_phone_empty') }}"
      else if($('#radio-briefing-yes').is(':checked') && $('#location_briefing').val() == "")
        message = "{{ __('general.briefing_location_empty') }}"
      else if($('#radio-briefing-yes').is(':checked') && $('#date_briefing').val() == "")
        message = "{{ __('general.briefing_date_empty') }}"
      else if(
        $('#radio-briefing-yes').is(':checked') && $('#radio-interview-yes').is(':checked') && 
        $('#date_briefing').val() != "" && $('#interview_date').val() != "" && 
        moment($('#interview_date').val(), 'DD-MM-YYYY HH:mm').isAfter(moment($('#date_briefing').val(), 'DD-MM-YYYY HH:mm'))
      )
        message = "{{ __('general.interview_date_after_briefing') }}"
      return message
    }

    function init_interview_date(){
      // $('#interview_date').datetimepicker('destroy')
      var defaultDate = '{{ !empty($jobs) && count($jobs->interview) > 0 ? $jobs->interview[0]->schedule->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->formatLocalized('%d-%m-%Y %H:%M') }}'
      $('#interview_date').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        useCurrent: false,
        defaultDate: moment(defaultDate, 'DD-MM-YYYY HH:mm'),
        // minDate: moment(defaultDate, 'DD-MM-YYYY HH:mm'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#interview_date').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_briefing_date()
        }
      })
    }

    function init_briefing_date(){
      // $('#date_briefing').datetimepicker('destroy')
      var defaultDate = '{{ !empty($jobs) && count($jobs->briefing) > 0 ? $jobs->briefing[0]->schedule->formatLocalized('%d-%m-%Y %H:%M') : (!empty($jobs) && count($jobs->interview) > 0 ? $jobs->interview[0]->schedule->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->formatLocalized('%d-%m-%Y %H:%M')) }}'
      $('#date_briefing').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        useCurrent: false,
        defaultDate: moment(defaultDate, 'DD-MM-YYYY HH:mm'),
        // minDate: $('#interview_date').val() !== "" ? moment($('#interview_date').val(), 'DD-MM-YYYY HH:mm') : moment().startOf('day'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })
    }
  </script>
@endpush

@push('afterScript')
init_interview_date()
init_briefing_date()


$('#radio-briefing-yes').click((e) => {
  $('#briefing-layout').removeClass('d-none')
})
$('#radio-briefing-no').click((e) => {
  $('#briefing-layout').addClass('d-none')
})
$('#radio-interview-yes').click((e) => {
  $('#interview-layout').removeClass('d-none')
})
$('#radio-interview-no').click((e) => {
  $('#interview-layout').addClass('d-none')
})
$('#radio-offline').click((e) => {
  $('#online_type').addClass('d-none')
  $('#offline_type').removeClass('d-none')
})
$('#radio-online').click((e) => {
  $('#online_type').removeClass('d-none')
  $('#offline_type').addClass('d-none')
})

$('#interviewer_phone').keyup(() => {
  $('#interviewer_phone').val(phone_validation($('#interviewer_phone').val()))
})
$('#person_in_charge_phone').keyup(() => {
  $('#person_in_charge_phone').val(phone_validation($('#person_in_charge_phone').val()))
})
@endpush