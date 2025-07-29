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
    <label>{{ __('general.question') }}</label>
    <input type="text" name="question" id="question" class="form-control"/>
  </div>

  <div id="option_container">
  </div>

  <div>
    <button class="btn btn-primary" type="button" onclick="add_option()">{{ __('general.add_option') }}</button>
  </div>

  <div class="mt-3">
    <button class="btn btn-outline-dark" type="button" onclick="cancel_general_quiz()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_general_quiz()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    var arr_option = []
    var num_min_option = 4
    function on_edit(index1){
      var general_quiz = arr_general_quiz[index1]
      index = index1
      is_publish = general_quiz.is_publish
      $('#question').val(general_quiz.question)
      $('#' + (general_quiz.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
      arr_option = general_quiz.arr_option
      manage_arr_option()
    }

    function on_delete(index){
      var general_quiz = arr_general_quiz[index]
      arr_general_quiz.splice(index, 1)
      manage_arr_general_quiz()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      $('#question').val('')
      arr_option = []
      for(let x = 0; x < num_min_option; x++){
        arr_option.push({
          name: '',
          symbol: String.fromCharCode(65 + arr_option.length),
          is_true: false,
        })
      }
      manage_arr_option()
    }

    function submit_general_quiz(){
      var option_message = ""
      var flag = false
      for(let x in arr_option){
        if(arr_option[x].name == ""){
          option_message = '{{ __("general.option_empty") }}'
          break
        }
        if(arr_option[x].is_true)
          flag = true
      }

      if($('#question').val() === "")
        notify_user('{{ __("general.question_empty") }}')
      else if(option_message)
        notify_user(option_message)
      else if(!flag)
        notify_user('{{ __("general.option_true_not_choosen") }}')
      else{
        var general_quiz = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          question: $('#question').val(),
          arr_option: arr_option,
        }
        if(index < 0)
          arr_general_quiz.push(general_quiz)
        else
          arr_general_quiz[index] = general_quiz
          
        reset()
        manage_arr_general_quiz()
      }
    }

    function cancel_general_quiz(){
      reset()
    }

    function reset_option_true(){
      for(let x in arr_option){
        arr_option[x].is_true = false
        $('#option'+x+'True').prop('checked', false)
      }
    }

    function manage_arr_option(){
      var str = ""
      
      for(let x in arr_option){
        str += `
          <div class="form-group">
            <label>{{ __('general.option') }} ${arr_option[x].symbol}</label>
            <div class="d-flex align-items-center">
              <input type="text" id="option${x}" key="${x}" value="${arr_option[x].name}" class="form-control optionClass"/>

              <div class="form-check ml-3">
                <input class="form-check-input optionTrueClass" type="checkbox" ${arr_option[x].is_true ? 'checked' : ''} value="1" key="${x}" id="option${x}True">
                <label class="form-check-label" for="option${x}True">
                  True
                </label>
              </div>
        `

        if(x >= num_min_option)
          str += `
                <div class="ml-3">
                  <button class="btn btn-danger optionRemoveClass" key="${x}" id="removeOption${x}">{{ __('general.delete') }}</button>
                </div>
          `
        str += `
            </div>
          </div>
        `
      }
      $('#option_container').html(str)

      $('.optionTrueClass').click(function() {
        reset_option_true()
        $('#option'+$(this).attr('key')+'True').prop('checked', true)
        arr_option[$(this).attr('key')].is_true = $('#option'+$(this).attr('key')+'True').is(':checked')
      })

      $('.optionRemoveClass').click(function() {
        arr_option.splice($(this).attr('key'), 1)
        manage_arr_option()
      })

      $('.optionClass').change(function(){
        arr_option[$(this).attr('key')].name = $('#option'+$(this).attr('key')).val()
      })
    }

    function add_option(){
      arr_option.push({
        name: '',
        symbol: String.fromCharCode(65 + arr_option.length),
        is_true: false,
      })
      manage_arr_option()
    }
    
    $(document).ready(() => {
      reset()

      $('#radio-publish').click(() => {
        is_publish = 1
      })

      $('#radio-not_publish').click(() => {
        is_publish = 0
      })

      

      manage_arr_general_quiz()
      
    })
  </script>
@endpush