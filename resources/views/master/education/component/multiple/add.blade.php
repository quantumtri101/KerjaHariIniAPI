<div>
  <div class="form-group">
    <label>{{ __('general.status_publish') }}</label>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="is_publish" value="1" id="radio-publish" checked required>
      <label class="form-check-label" for="radio-publish">
        {{ __('general.publish') }}
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="is_publish" value="0" id="radio-not_publish" required>
      <label class="form-check-label" for="radio-not_publish">
        {{ __('general.not_publish') }}
      </label>
    </div>
  </div>

  <div class="form-group">
    <label>{{ __('general.name') }}</label>
    <input type="text" name="name" id="name" class="form-control"/>
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_education()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_education()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var education = arr_education[index1]
      index = index1
      is_publish = education.is_publish
      $('#name').val(education.name)
      $('#' + (education.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
      url_image = education.url_image
    }

    function on_delete(index){
      var education = arr_education[index]
      arr_education.splice(index, 1)
      manage_arr_education()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      $('#name').val('')
    }

    function submit_education(){
      if($('#name').val() === "")
        notify_user('{{ __("general.name_empty") }}')
      else{
        var education = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          name: $('#name').val(),
        }
        if(index < 0)
          arr_education.push(education)
        else
          arr_education[index] = education
          
        reset()
        manage_arr_education()
      }
    }

    function cancel_education(){
      reset()
    }
    
    $(document).ready(() => {
      $('#radio-publish').click(() => {
        is_publish = 1
      })

      $('#radio-not_publish').click(() => {
        is_publish = 0
      })

      $('#name').keydown((e) => {
        if(e.key === "Enter"){
          submit_education()
          e.preventDefault()
        }
      })

      manage_arr_education()
    })
  </script>
@endpush