<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.name') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_education">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_education = []

    async function manage_arr_education(){
      var str = ""
      if(arr_education.length > 0){
        for(let x in arr_education){
          str += `
            <tr>
              <td>${arr_education[x].status_publish}</td>
              <td>${arr_education[x].name}</td>
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
        $('#arr_education').val(JSON.stringify(arr_education))
        $('#table_body_education').html(str)
      }, 1000);
    }
  </script>
@endpush