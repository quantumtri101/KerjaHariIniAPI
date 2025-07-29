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
    <tbody id="table_body_bank">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_bank = []

    async function manage_arr_bank(){
      var str = ""
      if(arr_bank.length > 0){
        for(let x in arr_bank){
          str += `
            <tr>
              <td>${arr_bank[x].status_publish}</td>
              <td>${arr_bank[x].name}</td>
              <td><img src="${arr_bank[x].url_image}" style="width: 10rem"/></td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `

          if(arr_bank[x].image_data != null && arr_bank[x].image_data != '')
            await getBase64(arr_bank[x].image_data, (response) => {
              arr_bank[x].image = response
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
        $('#arr_bank').val(JSON.stringify(arr_bank))
        $('#table_body_bank').html(str)
      }, 1000);
    }
  </script>
@endpush