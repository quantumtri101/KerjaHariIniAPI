<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.name') }}</th>
        <th>{{ __('general.image') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_qualification">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_qualification = []

    async function manage_arr_qualification(){
      var str = ""
      if(arr_qualification.length > 0){
        for(let x in arr_qualification){
          str += `
            <tr>
              <td>${arr_qualification[x].status_publish}</td>
              <td>${arr_qualification[x].name}</td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `

          await getBase64(arr_qualification[x].image_data, (response) => {
            arr_qualification[x].image = response
          })
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
        $('#arr_qualification').val(JSON.stringify(arr_qualification))
        $('#table_body_qualification').html(str)
      }, 1000);
    }
  </script>
@endpush