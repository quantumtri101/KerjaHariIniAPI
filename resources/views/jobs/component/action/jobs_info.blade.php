<div class="row">
  <div class="col-12">
    <div class="form-group">
      <label>{{ __('general.split_shift') }}</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_split_shift" value="1" {{ !empty($jobs) && count($jobs->shift) > 1 ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-split-yes">
        <label class="form-check-label" for="radio-split-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_split_shift" value="0" {{ !empty($jobs) && count($jobs->shift) == 1 ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-split-no">
        <label class="form-check-label" for="radio-split-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div id="with_split_shift" class="{{ !empty($jobs) && count($jobs->shift) > 1 ? '' : 'd-none' }}">
      {{-- <div class="form-group">
        <label>{{ __('general.date') }}</label>
        <input type="text" name="date" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="datetimepicker" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker"/>
      </div> --}}

      <div class="row">
        @for($x = 1; $x <= 2; $x++)
          <div class="col-6">
            <div class="form-group">
              <label>{{ __('general.start_date_num_shift', ["num" => $x]) }}</label>
              <div id="starttimepicker{{ $x }}error">
                <label >{{ __('general.datepicker_error') }}</label>
              </div>
              <input type="text" name="start_time{{ $x }}" id="starttimepicker{{ $x }}" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#starttimepicker{{ $x }}" value="{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[$x - 1]->start_date->formatLocalized('%d-%m-%Y %H:%M') : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label>{{ __('general.end_date_num_shift', ["num" => $x]) }}</label>
              <div id="endtimepicker{{ $x }}error">
                <label >{{ __('general.datepicker_error') }}</label>
              </div>
              <input type="text" name="end_time{{ $x }}" id="endtimepicker{{ $x }}" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#endtimepicker{{ $x }}" value="{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[$x - 1]->end_date->formatLocalized('%d-%m-%Y %H:%M') : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
            </div>
          </div>
        @endfor
      </div>
    </div>

    <div id="without_split_shift" class="{{ !empty($jobs) && count($jobs->shift) == 1 ? '' : 'd-none' }}">
      <div class="form-group">
        <label>{{ __('general.start_date') }}</label>
        <div id="startdatetimepickererror">
          <label >{{ __('general.datepicker_error') }}</label>
        </div>
        <input type="text" name="start_date" id="startdatetimepicker" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#startdatetimepicker" value="{{ !empty($jobs) && count($jobs->shift) == 1 ? $jobs->shift[0]->start_date->formatLocalized('%d-%m-%Y %H:%M') : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
      </div>

      <div class="form-group">
        <label>{{ __('general.end_date') }}</label>
        <div id="enddatetimepickererror">
          <label >{{ __('general.datepicker_error') }}</label>
        </div>
        <input type="text" name="end_date" id="enddatetimepicker" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#enddatetimepicker" value="{{ !empty($jobs) && count($jobs->shift) == 1 ? $jobs->shift[0]->end_date->formatLocalized('%d-%m-%Y %H:%M') : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.salary_type_regular') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="salary_type_regular" value="{{ $jobs->salary_type_regular }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" type="radio" name="salary_type_regular" value="1" {{ !empty($jobs) && $jobs->salary_type_regular == 'fixed' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-regular-fixed">
        <label class="form-check-label" for="radio-regular-fixed">
          {{ __('general.fixed') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="salary_type_regular" value="0" {{ !empty($jobs) && $jobs->salary_type_regular == 'per_hour' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-regular-per_hour">
        <label class="form-check-label" for="radio-regular-per_hour">
          {{ __('general.per_hour') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.salary_regular') }}</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">Rp.</span>
        </div>
        <input type="text" name="salary_regular" id="salary_regular" class="form-control" value="{{ !empty($jobs) ? number_format($jobs->salary_regular, 0, ',', '.') : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
        <div class="input-group-append {{ !empty($jobs) && $jobs->salary_type_regular == 'per_hour' ? '' : 'd-none' }}" id="salary_regular_per_hour">
          <span class="input-group-text" id="basic-addon1">/ Hour</span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.salary_type_casual') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="salary_type_casual" value="{{ $jobs->salary_type_casual }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" type="radio" name="salary_type_casual" value="1" {{ !empty($jobs) && $jobs->salary_type_casual == 'fixed' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-casual-fixed">
        <label class="form-check-label" for="radio-casual-fixed">
          {{ __('general.fixed') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="salary_type_casual" value="0" {{ !empty($jobs) && $jobs->salary_type_casual == 'per_hour' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-casual-per_hour">
        <label class="form-check-label" for="radio-casual-per_hour">
          {{ __('general.per_hour') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.salary_casual') }}</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">Rp.</span>
        </div>
        <input type="text" name="salary_casual" id="salary_casual" class="form-control" value="{{ !empty($jobs) ? number_format($jobs->salary_casual, 0, ',', '.') : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
        <div class="input-group-append {{ !empty($jobs) && $jobs->salary_type_casual == 'per_hour' ? '' : 'd-none' }}" id="salary_casual_per_hour">
          <span class="input-group-text" id="basic-addon1">/ Hour</span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.benefit') }}</label>
      <textarea name="benefit" id="benefit" class="form-control" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}>{{ !empty($jobs) ? $jobs->benefit : '' }}</textarea>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    {{-- <a class="btn btn-primary" onclick="save_current_page('{{ __('jobs.detail') }}')" href="{{ url('/jobs/action?id='.$jobs->id) }}">{{ __('general.cancel') }}</a>

    <a class="btn btn-primary ml-3" target="_blank" href="{{ url('/jobs/print-qr?id='.$jobs->id) }}">{{ __('general.next') }}</a> --}}
  </div>
</div>

@push('script')
  <script>
    var arrStartDateFirstTime = {}
    var arrEndDateFirstTime = {}

    function check_jobs(){
      var message = ""
      if($('#radio-split-no').is(':checked')){
        var start_time_no_split = moment($('#startdatetimepicker').val(), 'DD-MM-YYYY HH:mm')
        var end_time_no_split = moment($('#enddatetimepicker').val(), 'DD-MM-YYYY HH:mm')
      }

      if(!$('#radio-split-yes').is(':checked') && !$('#radio-split-no').is(':checked'))
        message = "{{ __('general.split_shift_not_choosen') }}"
      else if($('#radio-split-yes').is(':checked') && $('#datetimepicker').val() == "")
        message = "{{ __('general.date_empty') }}"
      else if($('#radio-split-yes').is(':checked')){
        for(let x = 1; x <= 2; x++){
          var start_time = moment($('#starttimepicker' + x).val(), 'DD-MM-YYYY HH:mm')
          var end_time = moment($('#endtimepicker' + x).val(), 'DD-MM-YYYY HH:mm')

          if($('#starttimepicker' + x).val() == ""){
            message = "{{ __('general.start_time_empty') }}"
            break
          }
          else if($('#endtimepicker' + x).val() == ""){
            message = "{{ __('general.end_time_empty') }}"
            break
          }
          else if(start_time.isAfter(end_time)){
            message = `Start date on Shift ${x} is after End date on Shift ${x}`
            break
          }
        }

        start_time = moment($('#starttimepicker2').val(), 'DD-MM-YYYY HH:mm')
        end_time = moment($('#endtimepicker1').val(), 'DD-MM-YYYY HH:mm')
        if(start_time.isAfter(end_time))
          message = `Start date on Shift 2 is after End date on Shift 1`
      }
      else if($('#radio-split-no').is(':checked') && $('#startdatetimepicker').val() == "")
        message = "{{ __('general.start_date_empty') }}"
      else if($('#radio-split-no').is(':checked') && $('#enddatetimepicker').val() == "")
        message = "{{ __('general.end_date_empty') }}"
      else if(start_time_no_split != null && end_time_no_split != null && start_time_no_split.isAfter(end_time_no_split))
        message = "{{ __('general.start_date_after_end') }}"
      else if(!$('#radio-regular-fixed').is(':checked') && !$('#radio-regular-per_hour').is(':checked'))
        message = "{{ __('general.salary_type_regular_not_choosen') }}"
      else if($('#salary_regular').val() == "" || $('#salary_regular').val() == "0")
        message = "{{ __('general.salary_regular_empty') }}"
      else if(!$('#radio-casual-fixed').is(':checked') && !$('#radio-casual-per_hour').is(':checked'))
        message = "{{ __('general.salary_type_casual_not_choosen') }}"
      else if($('#salary_casual').val() == "" || $('#salary_casual').val() == "0")
        message = "{{ __('general.salary_casual_empty') }}"
      return message
    }

    function init_start_date(){
      if(!arrStartDateFirstTime['#startdatetimepicker'])
        $('#startdatetimepicker').datetimepicker('destroy')
      arrStartDateFirstTime['#startdatetimepicker'] = false
      $('#startdatetimepickererror').addClass('d-none')
      $('#startdatetimepicker').removeClass('d-none')

      @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
        try{
          $('#startdatetimepicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            defaultDate: $('#startdatetimepicker').val() !== "" ? moment($('#startdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : ({{ !empty($jobs) ? 'true' : 'false' }} ? moment('{{ !empty($jobs) ? $jobs->shift[0]->start_date->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->addHours(1)->formatLocalized('%d-%m-%Y %H:%M') }}', 'DD-MM-YYYY HH:mm') : selected_event.start_date),
            minDate: selected_event.start_date != null ? selected_event.start_date : false,
            maxDate: $('#enddatetimepicker').val() !== "" ? moment($('#enddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : (selected_event.end_date != null ? selected_event.end_date : false),
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
            },
          })
        } catch(e){
          console.log(e)
          $('#startdatetimepickererror').removeClass('d-none')
          $('#startdatetimepicker').addClass('d-none')
        }
  
        // $('startdatetimepicker').on("show.datetimepicker", () => {
        //   $('#enddatetimepicker').datetimepicker('hide')
        // })
  
        // $('#startdatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        //   if(oldDate != null){
        //     if(arrStartDateFirstTime['#startdatetimepicker1'] == null || (arrStartDateFirstTime['#startdatetimepicker1'] != null && arrStartDateFirstTime['#startdatetimepicker1']))
        //       $('#enddatetimepicker').val(moment(date).add(1, 'minutes').format('DD-MM-YYYY HH:mm'))
        //     arrStartDateFirstTime['#startdatetimepicker1'] = false
        //     $('#startdatetimepicker').datetimepicker('hide')
  
        //     init_start_date()
        //     init_end_date()
        //   }
        // })
      @endif
    }

    function init_end_date(){
      if(!arrEndDateFirstTime['#enddatetimepicker'])
        $('#enddatetimepicker').datetimepicker('destroy')
      arrEndDateFirstTime['#enddatetimepicker'] = false
      $('#enddatetimepickererror').addClass('d-none')
      $('#enddatetimepicker').removeClass('d-none')
      
      @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
        try{
          $('#enddatetimepicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            defaultDate: $('#enddatetimepicker').val() !== "" ? moment($('#enddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : ({{ !empty($jobs) ? 'true' : 'false' }} ? moment('{{ !empty($jobs) ? $jobs->shift[0]->end_date->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->addDays(1)->formatLocalized('%d-%m-%Y %H:%M') }}', 'DD-MM-YYYY HH:mm') : selected_event.end_date),
            minDate: $('#startdatetimepicker').val() !== "" ? moment($('#startdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : (selected_event.start_date != null ? selected_event.start_date : moment().add(1, 'd').startOf('day')),
            maxDate: (selected_event.end_date != null ? selected_event.end_date : false),
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
            },
          })
        } catch(e){
          console.log(e)
          $('#enddatetimepickererror').removeClass('d-none')
          $('#enddatetimepicker').addClass('d-none')
        }
  
        // $('enddatetimepicker').on("show.datetimepicker", () => {
        //   $('#startdatetimepicker').datetimepicker('hide')
        // })
  
        // $('#enddatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        //   if(oldDate != null){
        //     if(arrStartDateFirstTime['#startdatetimepicker1'] == null || (arrStartDateFirstTime['#startdatetimepicker1'] != null && arrStartDateFirstTime['#startdatetimepicker1']))
        //       $('#startdatetimepicker').val(moment(date).subtract(1, 'm').format('DD-MM-YYYY HH:mm'))
        //     arrStartDateFirstTime['#startdatetimepicker1'] = false
        //     $('#enddatetimepicker').datetimepicker('hide')
  
        //     init_start_date()
        //     init_end_date()
        //   }
        // })
      @endif
    }

    function init_start_time(start_time_el, end_time_el, defaultTime, counter = 1, max_counter = 2){
      if(!arrStartDateFirstTime[start_time_el])
        $(start_time_el).datetimepicker('destroy')
      arrStartDateFirstTime[start_time_el] = false
      $(start_time_el + 'error').addClass('d-none')
      $(start_time_el).removeClass('d-none')
      
      @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
        try{
          $(start_time_el).datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            defaultDate: $(start_time_el).val() !== "" ? moment($(start_time_el).val(), 'DD-MM-YYYY HH:mm') : moment(defaultTime, 'DD-MM-YYYY HH:mm'),
            minDate: counter == 1 ? (selected_event.start_date != null ? selected_event.start_date.startOf('day') : moment().startOf('day')) : moment($('#endtimepicker' + (counter - 1)).val(), 'DD-MM-YYYY HH:mm'),
            maxDate: $(end_time_el).val() !== "" ? moment($(end_time_el).val(), 'DD-MM-YYYY HH:mm') : (selected_event.end_date != null ? selected_event.end_date.endOf('day') : moment().endOf('day')),
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
            },
          })
        } catch(e){
          console.log(e)
          $(start_time_el + 'error').removeClass('d-none')
          $(start_time_el).addClass('d-none')
        }
  
        // $(start_time_el).on("show.datetimepicker", () => {
        //   $('#starttimepicker1').datetimepicker(start_time_el == '#starttimepicker1' ? 'show' : 'hide')
        //   $('#starttimepicker2').datetimepicker(start_time_el == '#starttimepicker2' ? 'show' : 'hide')
        //   $('#endtimepicker1').datetimepicker('hide')
        //   $('#endtimepicker2').datetimepicker('hide')
        // })
  
        // $(start_time_el).on("change.datetimepicker", ({date, oldDate}) => {
        //   if(oldDate != null){
        //     init_end_time(start_time_el, end_time_el, defaultTime, counter, max_counter)
        //     if(counter > 1){
        //       init_end_time('#starttimepicker' + (counter - 1), '#endtimepicker' + (counter - 1), date.subtract(1, 'h'), counter - 1)
        //     }
        //   }
        // })
      @endif
    }

    function init_end_time(start_time_el, end_time_el, defaultTime, counter = 1, max_counter = 2){
      if(!arrEndDateFirstTime[end_time_el])
        $(end_time_el).datetimepicker('destroy')
      arrEndDateFirstTime[end_time_el] = false
      $(end_time_el + 'error').addClass('d-none')
      $(end_time_el).removeClass('d-none')
      // console.log($(start_time_el).val() !== "" ? moment($(start_time_el).val(), 'DD-MM-YYYY HH:mm') : moment().add(counter, 'd').startOf('day'))
      @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
        try{
          $(end_time_el).datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            defaultDate: $(end_time_el).val() !== "" ? moment($(end_time_el).val(), 'DD-MM-YYYY HH:mm') : moment(defaultTime, 'DD-MM-YYYY HH:mm'),
            minDate: $(start_time_el).val() !== "" ? moment($(start_time_el).val(), 'DD-MM-YYYY HH:mm') : (selected_event.start_date != null ? selected_event.start_date : moment().add(counter, 'd').startOf('day')),
            maxDate: counter == max_counter ? (selected_event.end_date != null ? selected_event.end_date : false) : ($('#starttimepicker' + (counter + 1)).val() != "" ? moment($('#starttimepicker' + (counter + 1)).val(), 'DD-MM-YYYY HH:mm') : moment($('#starttimepicker' + (counter)).val(), 'DD-MM-YYYY HH:mm').endOf('day')),
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
            },
          })
        } catch(e){
          console.log(e)
          $(end_time_el + 'error').removeClass('d-none')
          $(end_time_el).addClass('d-none')
        }
  
        // $(end_time_el).on("show.datetimepicker", () => {
        //   $('#endtimepicker1').datetimepicker(end_time_el == '#endtimepicker1' ? 'show' : 'hide')
        //   $('#endtimepicker2').datetimepicker(end_time_el == '#endtimepicker2' ? 'show' : 'hide')
        //   $('#starttimepicker1').datetimepicker('hide')
        //   $('#starttimepicker2').datetimepicker('hide')
        // })
  
        // $(end_time_el).on("change.datetimepicker", ({date, oldDate}) => {
        //   if(oldDate != null){
        //     init_start_time(start_time_el, end_time_el, defaultTime, counter, max_counter)
        //     if(counter < max_counter)
        //       init_start_time('#starttimepicker' + (counter + 1), '#endtimepicker' + (counter + 1), date.add(1, 'h'), counter + 1)
        //   }
        // })
      @endif
    }

    function init_date(){
      arrStartDateFirstTime['#startdatetimepicker'] = true
      arrStartDateFirstTime['#starttimepicker1'] = true
      arrStartDateFirstTime['#starttimepicker2'] = true
      arrEndDateFirstTime['#enddatetimepicker'] = true
      arrEndDateFirstTime['#endtimepicker1'] = true
      arrEndDateFirstTime['#endtimepicker2'] = true

      $('#datetimepicker').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: moment().subtract(1, 'days'),
        defaultDate: moment('{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[0]->start_date->formatLocalized('%d-%m-%Y') : \Carbon\Carbon::now()->addDays(3)->formatLocalized('%d-%m-%Y') }}', 'DD-MM-YYYY')
      })
      init_start_date()
      init_end_date()

      init_start_time('#starttimepicker1', '#endtimepicker1', ({{ !empty($jobs) && count($jobs->shift) > 1 ? 'true' : 'false' }} ? '{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[0]->start_date->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->formatLocalized('%d-%m-%Y %H:%M') }}' : selected_event.start_date), 1)
      init_end_time('#starttimepicker1', '#endtimepicker1', ({{ !empty($jobs) && count($jobs->shift) > 1 ? 'true' : 'false' }} ? '{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[0]->end_date->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->addMinutes(1)->formatLocalized('%d-%m-%Y %H:%M') }}' : selected_event.start_date.add(1, 'm')), 1)

      init_start_time('#starttimepicker2', '#endtimepicker2', ({{ !empty($jobs) && count($jobs->shift) > 1 ? 'true' : 'false' }} ? '{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[1]->start_date->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->addHours(1)->formatLocalized('%d-%m-%Y %H:%M') }}' : selected_event.start_date.add(1, 'h')), 2)
      init_end_time('#starttimepicker2', '#endtimepicker2', ({{ !empty($jobs) && count($jobs->shift) > 1 ? 'true' : 'false' }} ? '{{ !empty($jobs) && count($jobs->shift) > 1 ? $jobs->shift[1]->end_date->formatLocalized('%d-%m-%Y %H:%M') : \Carbon\Carbon::now()->addHours(1)->addMinutes(1)->formatLocalized('%d-%m-%Y %H:%M') }}' : selected_event.start_date.add(1, 'h').add(1, 'm')), 2)
    }

    $(document).ready(() => {
      $('.timepicker').click(function() {

      })
    })
  </script>
@endpush

@push('afterScript')
$('#salary_regular').keyup(() => {
  $('#salary_regular').val(to_currency_format($('#salary_regular').val()))
})
$('#salary_casual').keyup(() => {
  $('#salary_casual').val(to_currency_format($('#salary_casual').val()))
})
$('#radio-regular-fixed').click(() => {
  $('#salary_regular_per_hour').addClass('d-none')
})
$('#radio-regular-per_hour').click(() => {
  $('#salary_regular_per_hour').removeClass('d-none')
})
$('#radio-casual-fixed').click(() => {
  $('#salary_casual_per_hour').addClass('d-none')
})
$('#radio-casual-per_hour').click(() => {
  $('#salary_casual_per_hour').removeClass('d-none')
})
$('#radio-split-yes').click(() => {
  $('#with_split_shift').removeClass('d-none')
  $('#without_split_shift').addClass('d-none')
})
$('#radio-split-no').click(() => {
  $('#with_split_shift').addClass('d-none')
  $('#without_split_shift').removeClass('d-none')
})
@endpush