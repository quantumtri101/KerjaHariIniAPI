<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('general.status_publish') }}</th>
        <th>{{ __('general.image') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_banner">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_banner = []

    async function manage_arr_banner(){
      var str = ""
      if(arr_banner.length > 0){
        for(let x in arr_banner){
          str += `
            <tr>
              <td>${arr_banner[x].status_publish}</td>
              <td><img src="${arr_banner[x].url_image}" style="width: 10rem"/></td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `

          await getBase64(arr_banner[x].image_data, (response) => {
            arr_banner[x].image = response
          })
          // var img = new Image
          // img.src = arr_banner[x].url_image
          // img.onload = function() {
          //   $('#arr_banner_image')
          // }
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
        $('#arr_banner').val(JSON.stringify(arr_banner))
        $('#table_body_banner').html(str)
      }, 1000);
    }
  </script>
@endpush