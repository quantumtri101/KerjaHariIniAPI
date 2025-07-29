<div class="card" id="image_layout{{ !empty($id) ? $id : '' }}">
  <div class="card-body">
    <input type="file" name="{{ !empty($form_name) ? $form_name : 'image' }}" class="d-none" id="image{{ !empty($id) ? $id : '' }}" accept="{{ !empty($accept) ? $accept : 'image/jpeg, image/png' }}"/>

    <div>
      <button class="btn btn-primary"
        type="button"
        onclick="$('#image{{ !empty($id) ? $id : '' }}').trigger('click')">{{ __('general.upload_photo') }}</button>
      
      
    </div>

    <div class="mt-3" >
      <img id="image_show{{ !empty($id) ? $id : '' }}" style="width: 10rem"/>
      <p class="m-0" id="image_file_name{{ !empty($id) ? $id : '' }}"></p>

      <p class="m-0" id="width_height_notice"></p>
    </div>
  </div>
</div>

@push('script')
  <script>
    var url_image = '{{ !empty($data) && !empty($data->file_name) ? url($url_image."?file_name=".(!empty($column) ? $data->{$column} : $data->file_name)) : $url_asset."/image/no_image_available.jpeg" }}'
    var image = ""
    var max_size = 5
    var max_width = 0
    var max_height = 0

    // function on_change_image(){
    //   if(url_image !== ""){
    //     $('#image_show').removeClass("d-none")
    //     $('#image_show').attr("src", url_image)
    //   }
    //   else{
    //     $('#image_show').addClass("d-none")
    //   }
    // }

    function on_change_width_height(){
      $('#width_height_notice').html('Image must be in ' + max_width + 'x' + max_height + " resolution")
    }

    $(document).ready(() => {
      if(url_image !== ""){
        $('#image_show{{ !empty($id) ? $id : '' }}').removeClass("d-none")
        $('#image_show{{ !empty($id) ? $id : '' }}').attr("src", url_image)
      }
      else
        $('#image_show{{ !empty($id) ? $id : '' }}').addClass("d-none")

      $('#image{{ !empty($id) ? $id : '' }}').change((e) => {
        console.log(e.target.files[0])
        if(e.target.files[0].type == "image/jpeg" || e.target.files[0].type == "image/png"){
          $('#image_file_name{{ !empty($id) ? $id : '' }}').addClass('d-none')
          var img = new Image

          img.onload = function() {
            if((max_width > 0 && img.width > max_width) || (max_height > 0 && img.height > max_height)){
              notify_user('File melebihi resolusi yang diminta')
              return
            }

            url_image = URL.createObjectURL(e.target.files[0])
            image = e.target.files[0]
            
            if(url_image !== ""){
              $('#image_show{{ !empty($id) ? $id : '' }}').removeClass("d-none")
              $('#image_show{{ !empty($id) ? $id : '' }}').attr("src", url_image)
            }
            else
              $('#image_show{{ !empty($id) ? $id : '' }}').addClass("d-none")
          }

          img.src = URL.createObjectURL(e.target.files[0])

          if(e.target.files[0].size / 1024 / 1024 > max_size){
            notify_user('File melebihi '+max_size+" MB")
            return
          }
        }
        else{
          $('#image_file_name{{ !empty($id) ? $id : '' }}').removeClass('d-none')
          $('#image_show{{ !empty($id) ? $id : '' }}').addClass('d-none')

          $('#image_file_name{{ !empty($id) ? $id : '' }}').html(e.target.files[0].name)
        }
      })
    })
  </script>
@endpush
