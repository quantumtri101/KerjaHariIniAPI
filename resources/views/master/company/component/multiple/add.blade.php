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
    <label>{{ __('general.phone') }}</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
      </div>
      <input type="text" name="phone" id="phone" class="form-control"/>
    </div>
  </div>

  <div class="form-group">
    <label>{{ __('general.address') }}</label>
    <textarea name="address" id="address" class="form-control"></textarea>
  </div>

  <div class="form-group">
    <label>{{ __('general.image') }}</label>
    @include('layout.upload_photo', [
      "column" => "file_name",
      "form_name" => "image",
      "id" => "image",
      "url_image" => "/image/company",
    ])
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_company()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_company()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    function on_edit(index1){
      var company = arr_company[index1]
      index = index1
      is_publish = company.is_publish
      $('#name').val(company.name)
      $('#phone').val(company.phone)
      $('#address').val(company.address)
      $('#' + (company.is_publish == 1 ? 'radio-publish' : 'radio-not_publish')).prop('checked', true)
      $('#image_showimage').attr('src', company.url_image)
      url_image = company.url_image
    }

    function on_delete(index){
      var company = arr_company[index]
      arr_company.splice(index, 1)
      manage_arr_company()
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      $('#name').val('')
      $('#phone').val('')
      $('#address').val('')
      url_image = '{{ $url_asset."/image/no_image_available.jpeg" }}'
      $('#image_showimage').attr('src', url_image)
    }

    function submit_company(){
      if($('#name').val() === "")
        notify_user('{{ __("general.name_empty") }}')
      else if($('#phone').val() === "")
        notify_user('{{ __("general.phone_empty") }}')
      else if($('#address').val() === "")
        notify_user('{{ __("general.address_empty") }}')
      else if(url_image === "")
        notify_user('{{ __("general.image_empty") }}')
      else{
        var company = {
          is_publish: is_publish,
          status_publish: is_publish == 1 ? '{{ __("general.publish") }}' : '{{ __("general.not_publish") }}',
          name: $('#name').val(),
          phone: '+62' + $('#phone').val(),
          address: $('#address').val(),
          url_image: url_image,
          image_data: image,
        }
        if(index < 0)
          arr_company.push(company)
        else
          arr_company[index] = company
          
        reset()
        manage_arr_company()
      }
    }

    function cancel_company(){
      reset()
    }
    
    $(document).ready(() => {
      $('#radio-publish').click(() => {
        is_publish = 1
      })

      $('#radio-not_publish').click(() => {
        is_publish = 0
      })

      $('#name, #address').keydown((e) => {
        if(e.key === "Enter"){
          submit_company()
          e.preventDefault()
        }
      })

      $('#phone').keydown((e) => {
        if(e.key === "Enter"){
          submit_company()
          e.preventDefault()
        }
        else
          $('#phone').val(phone_validation($('#phone').val()))
      })

      manage_arr_company()
    })
  </script>
@endpush