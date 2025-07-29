<div>
  <div class="form-group d-none">
    <label>{{ __('general.status_publish') }}</label>
    <div class="form-check">
      <input class="form-check-input" type="radio" value="1" id="radio-qualification-publish" checked required>
      <label class="form-check-label" for="radio-qualification-publish">
        {{ __('general.publish') }}
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" value="0" id="radio-qualification-not_publish" required>
      <label class="form-check-label" for="radio-qualification-not_publish">
        {{ __('general.not_publish') }}
      </label>
    </div>
  </div>

  <div class="form-group">
    <label>{{ __('general.qualification') }}</label>
    <input type="text" id="qualification_name" class="form-control"/>
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_qualification()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_qualification()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var qualification = arr_qualification[index1]
      index = index1
      is_publish = qualification.is_publish
      $('#qualification_name').val(qualification.name)
      $('#' + (qualification.is_publish == 1 ? 'radio-qualification-publish' : 'radio-qualification-not_publish')).prop('checked', true)
    }

    function on_delete(index){
      var qualification = arr_qualification[index]
      arr_qualification.splice(index, 1)
      manage_arr_qualification()
    }

    function reset(){
      is_publish = 1
      $('#radio-qualification-publish').prop('checked', true)
      $('#qualification_name').val('')
    }

    function submit_qualification(){
      if($('#qualification_name').val() === "")
        notify_user('{{ __("general.name_empty") }}')
      else{
        var flag = false
        for(let qualification of arr_qualification){
          if(qualification.name === $('#qualification_name').val()){
            flag = true
            break
          }
        }

        if(!flag){
          var qualification = {
            is_publish: is_publish,
            status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
            name: $('#qualification_name').val(),
          }
          if(index < 0)
            arr_qualification.push(qualification)
          else
            arr_qualification[index] = qualification
            
          reset()
          manage_arr_qualification()
        }
        else
          reset()
      }
    }

    function cancel_qualification(){
      reset()
    }
    
    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
$('#radio-qualification-publish').click(() => {
  is_publish = 1
})

$('#radio-qualification-not_publish').click(() => {
  is_publish = 0
})

manage_arr_qualification()
@endpush