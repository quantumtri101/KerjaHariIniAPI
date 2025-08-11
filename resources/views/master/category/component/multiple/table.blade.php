<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.category') }}</th>
        <th>{{ __('general.image') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_category">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_category = []

    async function manage_arr_category(){
      var str = ""
      if(arr_category.length > 0){
        for(let x in arr_category){
          str += `
            <tr>
              <td>${arr_category[x].status_publish}</td>
              <td>${arr_category[x].name}</td>
              <td><img src="${arr_category[x].url_image}" style="width: 10rem"/></td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `

          if(arr_category[x].url_image != '{{ $url_asset."/image/no_image_available.jpeg" }}')
            await getBase64(arr_category[x].image_data, (response) => {
              arr_category[x].image = response
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
        $('#arr_category').val(JSON.stringify(arr_category))
        $('#table_body_category').html(str)
      }, 1000);
    }
  </script>
@endpush