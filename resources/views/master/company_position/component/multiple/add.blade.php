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
    <button class="btn btn-outline-dark" type="button" onclick="cancel_company_position()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_company_position()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var company_position = arr_company_position[index1]
      index = index1
      is_publish = company_position.is_publish
      $('#name').val(company_position.name)
      $('#' + (company_position.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
    }

    function on_delete(index){
      var company_position = arr_company_position[index]
      arr_company_position.splice(index, 1)
      manage_arr_company_position()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      $('#name').val('')
    }

    function submit_company_position(){
      if($('#name').val() === "")
        notify_user('{{ __("general.name_empty") }}')
      else{
        var company_position = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          name: $('#name').val(),
        }
        if(index < 0)
          arr_company_position.push(company_position)
        else
          arr_company_position[index] = company_position
          
        reset()
        manage_arr_company_position()
      }
    }

    function cancel_company_position(){
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
          submit_company_position()
          e.preventDefault()
        }
      })

      manage_arr_company_position()
    })
  </script>
@endpush