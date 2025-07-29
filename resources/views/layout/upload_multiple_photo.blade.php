<div class="card" id="image_layout{{ !empty($id) ? $id : '' }}">
  <div class="card-body">
    <input type="hidden" name="arr_image" id="arr_image{{ !empty($id) ? $id : '' }}"/>
    <input type="file" name="{{ !empty($form_name) ? $form_name : 'image[]' }}" multiple class="d-none" id="image{{ !empty($id) ? $id : '' }}" accept="{{ !empty($accept) ? $accept : 'image/jpeg, image/png' }}"/>

    <button class="btn btn-primary"
      type="button"
      onclick="$('#image{{ !empty($id) ? $id : '' }}').trigger('click')">{{ __('general.upload_photo') }}</button>

    <div class="mt-3">
      <p class="m-0" id="extension_notice{{ !empty($id) ? $id : '' }}"></p>
      <p class="m-0" id="width_height_multiple_notice{{ !empty($id) ? $id : '' }}"></p>
    </div>

    <div class="mt-3" id="image_column{{ !empty($id) ? $id : '' }}">
    </div>
  </div>
</div>

@push('script')
  <script>
    var arr_image = []
    var arr_id = []
    var arr_url_image = []
    var arr_file_name = []
    var arr_name_display = []
    var arr_response = []
    var max_size = 5
    var max_width = 0
    var max_height = 0
    var counter_image = 0

    function on_change_multiple_width_height{{ !empty($id) ? $id : '' }}(){
      $('#width_height_multiple_notice{{ !empty($id) ? $id : '' }}').html('Image must be in ' + max_width + 'x' + max_height + " resolution")
    }

    async function on_change_multiple_image{{ !empty($id) ? $id : '' }}(){
      arr_response = []

      var str = ""
      str += `<div class="row">`
      for(let x in arr_url_image){
        str += `<div class="col-6 mt-3">`
          str += `<div class="position-relative ml-1">`
            if(arr_name_display[x] == null){
              str += `<button class="btn btn-danger position-absolute" style="right: 0" onclick="{{ !empty($id) ? $id : '' }}(${x})"><i class="fa-solid fa-trash m-0"></i></button>`
              str += `<img src="${arr_url_image[x]}" counter="${x}" width="100%" class="image_show " />`
            }
            else{
              str += `
              <div class="d-flex justify-content-between align-items-center">
                <p class="m-0">${arr_name_display[x]}</p>
                <button class="btn btn-danger" onclick="on_remove_clicked{{ !empty($id) ? $id : '' }}(${x})"><i class="fa-solid fa-trash m-0"></i></button>
              </div>
              `
            }
          str += `</div>`
        str += `</div>`

        if(arr_name_display[x] == null)
          arr_response.push({
            id: arr_id[x],
            image: "",
          })
        else
          arr_response.push({
            id: arr_id[x],
            image: "",
            file_name: arr_name_display[x],
            file: "",
          })

        if(arr_image[x] != null){
          await getBase64(arr_image[x], (response) => {
            if(arr_name_display[x] == null)
              arr_response[x].image = response
            else{
              arr_response[x].image = response
              arr_response[x].file = response
            }
            counter_image++
            $('#arr_image{{ !empty($id) ? $id : '' }}').val(JSON.stringify(arr_response))
          })
        }
      }
      str += `</div>`
      $('#image_column{{ !empty($id) ? $id : '' }}').html(str)

      $('#arr_image{{ !empty($id) ? $id : '' }}').val(JSON.stringify(arr_response))
    }

    function on_remove_clicked{{ !empty($id) ? $id : '' }}(index){
      arr_image.splice(index, 1)
      arr_url_image.splice(index, 1)
      arr_file_name.splice(index, 1)
      on_change_multiple_image{{ !empty($id) ? $id : '' }}()
    }

    $(document).ready(() => {
      on_change_multiple_image{{ !empty($id) ? $id : '' }}()

      $('#image{{ !empty($id) ? $id : '' }}').change(function(e) {
        console.log(e.target.files)
        var flag = false
        for(let file of e.target.files){
          if(file.size / 1024 / 1024 > max_size){
            notify_user('File melebihi '+max_size+" MB")
            return
          }

          var img = new Image
          img.onload = function() {
            if(flag)
              return
            if((max_width > 0 && img.width > max_width) || (max_height > 0 && img.height > max_height)){
              notify_user('File melebihi resolusi yang diminta')
              flag = true
              return
            }
          }
          img.src = URL.createObjectURL(file)
        }

        setTimeout(() => {
          
          if(!flag){
            for(let file of e.target.files){
              arr_image.push(file)
              arr_url_image.push(URL.createObjectURL(file))
              arr_file_name.push(null)
              arr_name_display.push(file.name)
              arr_id.push(null)
            }
            on_change_multiple_image{{ !empty($id) ? $id : '' }}()
          }
        }, 500);
        
      })

      // $('.image_show').click(() => {
      //   var index = $(this).attr('counter')
      //   arr_image.splice(index, 1)
      //   on_change_multiple_image{{ !empty($id) ? $id : '' }}()
      // })

      
    })
  </script>
@endpush
