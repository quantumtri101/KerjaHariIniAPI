<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.name') }}</th>
        <th>{{ __('general.phone') }}</th>
        <th>{{ __('general.address') }}</th>
        <th>{{ __('general.image') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_company">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_company = []

    async function manage_arr_company(){
      var str = ""
      if(arr_company.length > 0){
        for(let x in arr_company){
          str += `
            <tr>
              <td>${arr_company[x].status_publish}</td>
              <td>${arr_company[x].name}</td>
              <td>${arr_company[x].phone}</td>
              <td>${arr_company[x].address}</td>
              <td><img src="${arr_company[x].url_image}" style="width: 10rem"/></td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `
          await getBase64(arr_company[x].image_data, (response) => {
            arr_company[x].image = response
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
        $('#arr_company').val(JSON.stringify(arr_company))
        $('#table_body_company').html(str)
      }, 1000);
    }
  </script>
@endpush