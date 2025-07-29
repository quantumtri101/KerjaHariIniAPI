<div>
  <div class="form-group">
    <label>{{ __('general.status_publish') }}</label>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="is_publish" value="1" id="radio-publish" checked required>
      <label class="form-check-label" for="radio-publish">
        {{ __('general.publish') }}
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="is_publish" value="0" id="radio-not_publish" required>
      <label class="form-check-label" for="radio-not_publish">
        {{ __('general.not_publish') }}
      </label>
    </div>
  </div>

  <div class="form-group">
    <label>{{ __('general.image') }}</label>
    @include('layout.upload_photo', [
      "column" => "file_name",
      "form_name" => "image",
      "id" => "image",
      "url_image" => "/image/banner",
    ])
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_banner()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_banner()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var banner = arr_banner[index1]
      index = index1
      is_publish = banner.is_publish
      $('#' + (banner.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
      $('#image_showimage').attr('src', banner.url_image)
      url_image = banner.url_image
    }

    function on_delete(index){
      var banner = arr_banner[index]
      arr_banner.splice(index, 1)
      manage_arr_banner()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      url_image = '{{ $url_asset."/image/no_image_available.jpeg" }}'
      $('#image_showimage').attr('src', url_image)
    }

    function submit_banner(){
      if(url_image === "")
        notify_user('{{ __("general.image_empty") }}')
      else{
        var banner = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          url_image: url_image,
          image_data: image,
        }
        if(index < 0)
          arr_banner.push(banner)
        else
          arr_banner[index] = banner
          
        reset()
        manage_arr_banner()
      }
    }

    function cancel_banner(){
      reset()
    }
    
    $(document).ready(() => {
      $('#radio-publish').click(() => {
        is_publish = 1
      })

      $('#radio-not_publish').click(() => {
        is_publish = 0
      })

      manage_arr_banner()
    })
  </script>
@endpush