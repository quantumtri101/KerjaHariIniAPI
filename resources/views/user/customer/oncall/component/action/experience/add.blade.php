<div>
  <div class="form-group">
    <label>{{ __('general.experience') }}</label>
    <input type="text" id="experience_name" class="form-control"/>
  </div>

  <div class="form-group">
    <label>{{ __('general.start_year') }}</label>
    <input type="text" id="experience_start_year" class="form-control"/>
  </div>

  <div class="form-group">
    <label>{{ __('general.end_year') }}</label>
    <input type="text" id="experience_end_year" class="form-control"/>
  </div>

  <div class="form-group">
    <label>{{ __('general.company') }}</label>
    <input type="text" id="experience_company" class="form-control"/>
  </div>

  <div class="form-group">
    <label>{{ __('general.location') }}</label>
    <select class="form-control" id="experience_location"></select>
  </div>

  <div class="form-group">
    <label>{{ __('general.description') }}</label>
    <textarea id="experience_description" class="form-control"></textarea>
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_experience()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_experience()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    var arr_city = []
    function on_edit_experience(index1){
      var experience = arr_experience[index1]
      index = index1
      is_publish = experience.is_publish
      $('#experience_name').val(experience.name)
      $('#experience_start_year').val(experience.start_year)
      $('#experience_end_year').val(experience.end_year)
      $('#experience_company').val(experience.company)
      $('#experience_location').val(experience.location)
      $('#experience_description').val(experience.description)
    }

    function on_delete_experience(index){
      var experience = arr_experience[index]
      arr_experience.splice(index, 1)
      manage_arr_experience()
    }

    function reset_experience(){
      is_publish = 1
      $('#experience_name').val('')
      $('#experience_start_year').val('')
      $('#experience_end_year').val('')
      $('#experience_company').val('')
      $('#experience_location').val('')
      $('#experience_description').val('')
    }

    function submit_experience(){
      if($('#experience_name').val() === "")
        notify_user('{{ __("general.experience_empty") }}')
      else if($('#experience_start_year').val() === "")
        notify_user('{{ __("general.start_year_empty") }}')
      else if($('#experience_end_year').val() === "")
        notify_user('{{ __("general.end_year_empty") }}')
      else if($('#experience_company').val() === "")
        notify_user('{{ __("general.company_empty") }}')
      else if($('#experience_location').val() === "")
        notify_user('{{ __("general.location_empty") }}')
      else if($('#experience_description').val() === "")
        notify_user('{{ __("general.description_empty") }}')
      else if(str_to_double($('#experience_start_year').val()) > str_to_double($('#experience_end_year').val()))
        notify_user('{{ __("general.start_year_exceed_end_year") }}')
      else{
        var flag = false
        for(let experience of arr_experience){
          if(experience.name === $('#experience_name').val()){
            flag = true
            break
          }
        }
        
        var selected_city = {}
        for(let city of arr_city){
          if(city.id === $('#experience_location').val()){
            selected_city = city
            break
          }
        }

        if(!flag){
          var experience = {
            name: $('#experience_name').val(),
            start_year: str_to_double($('#experience_start_year').val()),
            end_year: str_to_double($('#experience_end_year').val()),
            company: $('#experience_company').val(),
            location: selected_city.name,
            city: {
              id: $('#experience_location').val(),
            },
            description: $('#experience_description').val(),
          }
          if(index < 0)
            arr_experience.push(experience)
          else
            arr_experience[index] = experience
            
          reset_experience()
          manage_arr_experience()
        }
        else
          reset_experience()
      }
    }

    function cancel_experience(){
      reset_experience()
    }
    
    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')

manage_arr_experience()
$('#experience_start_year').keyup(() => {
  $('#experience_start_year').val(phone_validation($('#experience_start_year').val(), 4))
})
$('#experience_end_year').keyup(() => {
  $('#experience_end_year').val(phone_validation($('#experience_end_year').val(), 4))
})
$('#experience_location').select2({
  ajax: {
    url: '{{ url('/api/city/all') }}',
    dataType: 'json',
    processResults: function (data) {
      // Transforms the top-level key of the response object from 'items' to 'results'
      arr_city = data.data
      return {
        results: data.data
      };
    }
  },
})
@endpush