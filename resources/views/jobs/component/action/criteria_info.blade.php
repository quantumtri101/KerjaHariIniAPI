<div class="row">
  <div class="col-12">
    <div class="form-group">
      <label>{{ __('general.gender') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="gender" value="{{ $jobs->criteria[0]->gender }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" type="radio" name="gender" value="male" {{ !empty($jobs) && $jobs->criteria[0]->gender == 'male' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-male">
        <label class="form-check-label" for="radio-male">
          {{ __('general.male') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="gender" value="female" {{ !empty($jobs) && $jobs->criteria[0]->gender == 'female' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-female">
        <label class="form-check-label" for="radio-female">
          {{ __('general.female') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="gender" value="both" {{ !empty($jobs) && $jobs->criteria[0]->gender == 'both' ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-both">
        <label class="form-check-label" for="radio-both">
          {{ __('general.both') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.education') }}</label>
      <select name="education_id" id="education_id" class="form-control" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}>
        <option value="">{{ __('general.choose_education') }}</option>
        @foreach($arr_education as $education)
          <option value="{{ $education->id }}" {{ !empty($jobs) && $education->id == $jobs->criteria[0]->education->id ? 'selected' : '' }}>{{ $education->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>{{ __('general.age_range') }}</label>
      <div class="row">
        <div class="col">
          <input type="text" name="min_age" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="min_age" class="form-control" value="{{ !empty($jobs) ? $jobs->criteria[0]->min_age : '' }}"/>
        </div>
        <div class="col">
          <input type="text" name="max_age" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="max_age" class="form-control" value="{{ !empty($jobs) ? $jobs->criteria[0]->max_age : '' }}"/>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.has_pkwt') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="has_pkwt" value="{{ $jobs->criteria[0]->has_pkwt }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="has_pkwt" value="1" {{ !empty($jobs) && $jobs->criteria[0]->has_pkwt == 1 ? 'checked' : '' }} id="radio-pkwt-yes">
        <label class="form-check-label" for="radio-pkwt-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="has_pkwt" value="0" {{ !empty($jobs) && $jobs->criteria[0]->has_pkwt == 0 ? 'checked' : '' }} id="radio-pkwt-no">
        <label class="form-check-label" for="radio-pkwt-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.has_pkhl') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="has_pkhl" value="{{ $jobs->criteria[0]->has_pkhl }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="has_pkhl" value="1" {{ !empty($jobs) && $jobs->criteria[0]->has_pkhl == 1 ? 'checked' : '' }} id="radio-pkhl-yes">
        <label class="form-check-label" for="radio-pkhl-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="has_pkhl" value="0" {{ !empty($jobs) && $jobs->criteria[0]->has_pkhl == 0 ? 'checked' : '' }} id="radio-pkhl-no">
        <label class="form-check-label" for="radio-pkhl-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.is_working_same_company') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="is_working_same_company" value="{{ $jobs->criteria[0]->is_working_same_company }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="is_working_same_company" value="1" {{ !empty($jobs) && $jobs->criteria[0]->is_working_same_company == 1 ? 'checked' : '' }} id="radio-working_same-yes">
        <label class="form-check-label" for="radio-working_same-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="is_working_same_company" value="0" {{ !empty($jobs) && $jobs->criteria[0]->is_working_same_company == 0 ? 'checked' : '' }} id="radio-working_same-no">
        <label class="form-check-label" for="radio-working_same-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.working_area') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="arr_working_area_json" value="{{ json_encode($jobs->arr_working_area) }}" />
      @endif
      <select class="form-control select2" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="working_area" name="working_area[]" data-placeholder="Choose City" multiple="multiple">
        @foreach($arr_working_area as $working_area)
          <option value="{{ $working_area->id }}" {{ $working_area->is_selected ? 'selected' : '' }}>{{ $working_area->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group d-none">
      <label>{{ __('general.is_same_place') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="is_same_place" value="{{ $jobs->criteria[0]->is_same_place }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="is_same_place" value="1" {{ !empty($jobs) && $jobs->criteria[0]->is_same_place == 1 ? 'checked' : '' }} id="radio-same_place-yes">
        <label class="form-check-label" for="radio-same_place-yes">
          {{ __('general.yes') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} type="radio" name="is_same_place" value="0" {{ !empty($jobs) && $jobs->criteria[0]->is_same_place == 0 ? 'checked' : '' }} id="radio-same_place-no">
        <label class="form-check-label" for="radio-same_place-no">
          {{ __('general.no') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.other_criteria') }}</label>
      <textarea name="other_criteria" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="other_criteria" class="form-control">{{ !empty($jobs) ? $jobs->criteria[0]->other_criteria : '' }}</textarea>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    {{-- <a class="btn btn-primary" onclick="save_current_page('{{ __('jobs.detail') }}')" href="{{ url('/jobs/action?id='.$jobs->id) }}">{{ __('general.cancel') }}</a>

    <a class="btn btn-primary ml-3" target="_blank" href="{{ url('/jobs/print-qr?id='.$jobs->id) }}">{{ __('general.next') }}</a> --}}
  </div>
</div>

@push('script')
  <script>
    function check_criteria(){
      var message = ""
      if(!$('#radio-male').is(':checked') && !$('#radio-female').is(':checked') && !$('#radio-both').is(':checked'))
        message = "{{ __('general.gender_not_choosen') }}"
      else if($('#education_id').val() == "")
        message = "{{ __('general.education_not_choosen') }}"
      else if($('#min_age').val() == "" || $('#min_age').val() == "0")
        message = "{{ __('general.min_age_empty') }}"
      else if($('#max_age').val() == "" || $('#max_age').val() == "0")
        message = "{{ __('general.max_age_empty') }}"
      else if(str_to_double($('#min_age').val()) > str_to_double($('#max_age').val()))
        message = "Min age is more than Max age"
      else if(!$('#radio-pkwt-yes').is(':checked') && !$('#radio-pkwt-no').is(':checked'))
        message = "{{ __('general.has_pkwt_not_choosen') }}"
      else if(!$('#radio-pkhl-yes').is(':checked') && !$('#radio-pkhl-no').is(':checked'))
        message = "{{ __('general.has_pkhl_not_choosen') }}"
      else if(!$('#radio-working_same-yes').is(':checked') && !$('#radio-working_same-no').is(':checked'))
        message = "{{ __('general.working_same_company_not_choosen') }}"
      else if($('#working_area').val() == "")
        message = "{{ __('general.working_area_empty') }}"
      // else if(!$('#radio-same_place-yes').is(':checked') && !$('#radio-same_place-no').is(':checked'))
      //   message = "{{ __('general.same_place_not_choosen') }}"
      return message
    }

    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
$('#working_area').select2()

$('#salary_regular').keyup(() => {
  $('#salary_regular').val(to_currency_format($('#salary_regular').val()))
})
$('#salary_casual').keyup(() => {
  $('#salary_casual').val(to_currency_format($('#salary_casual').val()))
})
$('#min_age').keyup(() => {
  min_age = str_to_double($('#min_age').val())
  max_age = str_to_double($('#max_age').val())

  $('#min_age').val(to_currency_format($('#min_age').val()))
  //if(min_age > max_age)
  //  $('#max_age').val(to_currency_format($('#min_age').val()))
})
$('#max_age').keyup(() => {
  min_age = str_to_double($('#min_age').val())
  max_age = str_to_double($('#max_age').val())

  $('#max_age').val(to_currency_format($('#max_age').val()))
  //if(min_age > max_age)
  //  $('#min_age').val(to_currency_format($('#max_age').val()))
})
$('#radio-yes').click(() => {
  $('#with_split_shift').removeClass('d-none')
  $('#without_split_shift').addClass('d-none')
})
$('#radio-no').click(() => {
  $('#with_split_shift').addClass('d-none')
  $('#without_split_shift').removeClass('d-none')
})
@endpush