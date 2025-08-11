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
    <label>{{ __('general.name') }}</label>
    <input type="text" name="name" id="name" class="form-control"/>
  </div>

  <div class="form-group">
    <label>{{ __('general.image') }}</label>
    @include('layout.upload_photo', [
      "column" => "file_name",
      "form_name" => "image",
      "id" => "image",
      "url_image" => "/image/category",
    ])
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_category()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_category()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var category = arr_category[index1]
      index = index1
      is_publish = category.is_publish
      $('#name').val(category.name)
      $('#' + (category.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
      $('#image_showimage').attr('src', category.url_image)
      url_image = category.url_image
    }

    function on_delete(index){
      var category = arr_category[index]
      arr_category.splice(index, 1)
      manage_arr_category()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      $('#name').val('')
      url_image = '{{ $url_asset."/image/no_image_available.jpeg" }}'
      $('#image_showimage').attr('src', url_image)
    }

    function submit_category(){
      if($('#name').val() === "")
        notify_user('{{ __("general.name_empty") }}')
      // else if(url_image === "")
      //   notify_user('{{ __("general.image_empty") }}')
      else{
        var category = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          name: $('#name').val(),
          url_image: url_image,
          image_data: image,
        }
        if(index < 0)
          arr_category.push(category)
        else
          arr_category[index] = category
          
        reset()
        manage_arr_category()
      }
    }

    function cancel_category(){
      reset()
    }
    
    $(document).ready(() => {
      $('#radio-publish').click(() => {
        is_publish = 1
      })

      $('#radio-not_publish').click(() => {
        is_publish = 0
      })

      $('#name').keydown((e) => {
        if(e.key === "Enter"){
          submit_category()
          e.preventDefault()
        }
      })

      manage_arr_category()
    })
  </script>
@endpush