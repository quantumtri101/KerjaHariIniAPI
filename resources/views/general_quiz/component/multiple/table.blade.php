<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.question') }}</th>
        <th>{{ __('general.option') }}</th>
        <th>{{ __('general.option_true') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_general_quiz">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_general_quiz = []

    async function manage_arr_general_quiz(){
      var str = ""
      if(arr_general_quiz.length > 0){
        for(let x in arr_general_quiz){
          var option_true = {}
          var str_option = ''
          var counter = 0
          for(let option of arr_general_quiz[x].arr_option){
            str_option += option.symbol + ": " + option.name + (counter < arr_general_quiz[x].arr_option.length - 1 ? ', ' : '')
            if(option.is_true)
              option_true = option
            counter++
          }
          str += `
            <tr>
              <td>${arr_general_quiz[x].status_publish}</td>
              <td>${arr_general_quiz[x].question}</td>
              <td>${str_option}</td>
              <td>${option_true.symbol}</td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `
        }
      }
      else
        str += `
          <tr>
            <td colspan="5" class="text-center">{{ __('general.no_data') }}</td>
          </tr>
        `
      // $('#please_wait_modal').modal('show')
      // setTimeout(() => {
      //   $('#please_wait_modal').modal('hide')
        $('#arr_general_quiz').val(JSON.stringify(arr_general_quiz))
        $('#table_body_general_quiz').html(str)
      // }, 1000);
    }
  </script>
@endpush