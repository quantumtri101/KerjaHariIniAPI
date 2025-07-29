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
    <label>{{ __('general.min_salary') }}</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Rp.</span>
      </div>
      <input type="text" required name="min_salary" id="min_salary" class="form-control" value="0"/>
    </div>
  </div>

  <div class="form-group">
    <label>{{ __('general.max_salary') }}</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Rp.</span>
      </div>
      <input type="text" required name="max_salary" id="max_salary" class="form-control" value="0"/>
    </div>
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_range_salary()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_range_salary()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var range_salary = arr_range_salary[index1]
      index = index1
      is_publish = range_salary.is_publish
      $('#min_salary').val(range_salary.min_salary.toLocaleString(locale_string))
      $('#max_salary').val(range_salary.max_salary.toLocaleString(locale_string))
      $('#' + (range_salary.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
    }

    function on_delete(index){
      var range_salary = arr_range_salary[index]
      arr_range_salary.splice(index, 1)
      manage_arr_range_salary()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      $('#min_salary').val('0')
      $('#max_salary').val('0')
    }

    function submit_range_salary(){
      min_salary = str_to_double($('#min_salary').val())
      max_salary = str_to_double($('#max_salary').val())

      if(min_salary == 0)
        notify_user('{{ __("general.min_salary_empty") }}')
      else if(max_salary == 0)
        notify_user('{{ __("general.max_salary_empty") }}')
      else if(min_salary > max_salary)
        notify_user('{{ __("general.min_salary_higher_than_max_salary") }}')
      else{
        var range_salary = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          min_salary: str_to_double($('#min_salary').val()),
          max_salary: str_to_double($('#max_salary').val()),
        }
        if(index < 0)
          arr_range_salary.push(range_salary)
        else
          arr_range_salary[index] = range_salary
          
        reset()
        manage_arr_range_salary()
      }
    }

    function cancel_range_salary(){
      reset()
    }
    
    $(document).ready(() => {
      $('#radio-publish').click(() => {
        is_publish = 1
      })

      $('#radio-not_publish').click(() => {
        is_publish = 0
      })

      $('#min_salary').keydown((e) => {
        if(e.key === "Enter"){
          submit_range_salary()
          e.preventDefault()
        }
      })

      $('#min_salary').keyup((e) => {
        // var min_salary = str_to_double($('#min_salary').val())
        // var max_salary = str_to_double($('#max_salary').val())

        // if(min_salary > max_salary && max_salary > 0)
        //   $('#min_salary').val(to_currency_format($('#max_salary').val()))
        // else
        //   $('#min_salary').val(to_currency_format($('#min_salary').val()))

        $('#min_salary').val(to_currency_format($('#min_salary').val()))
      })

      $('#max_salary').keydown((e) => {
        if(e.key === "Enter"){
          submit_range_salary()
          e.preventDefault()
        }
      })

      $('#max_salary').keyup((e) => {
        // var min_salary = str_to_double($('#min_salary').val())
        // var max_salary = str_to_double($('#max_salary').val())

        // if(max_salary < min_salary && min_salary > 0)
        //   $('#max_salary').val(to_currency_format($('#min_salary').val()))
        // else
        //   $('#max_salary').val(to_currency_format($('#max_salary').val()))

        $('#max_salary').val(to_currency_format($('#max_salary').val()))
      })

      manage_arr_range_salary()
    })
  </script>
@endpush