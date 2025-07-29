<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.range_salary') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_range_salary">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_range_salary = []

    async function manage_arr_range_salary(){
      var str = ""
      if(arr_range_salary.length > 0){
        for(let x in arr_range_salary){
          str += `
            <tr>
              <td>${arr_range_salary[x].status_publish}</td>
              <td>Rp. ${arr_range_salary[x].min_salary.toLocaleString(locale_string)} - ${arr_range_salary[x].max_salary.toLocaleString(locale_string)}</td>
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
      $('#please_wait_modal').modal('show')
      setTimeout(() => {
        $('#please_wait_modal').modal('hide')
        $('#arr_range_salary').val(JSON.stringify(arr_range_salary))
        $('#table_body_range_salary').html(str)
      }, 1000);
    }
  </script>
@endpush