<div>
  <div class="form-group">
    <label>{{ __('general.start_date') }}</label>
    <input type="text" required name="start_date" id="shiftStartdatetimepicker" class="form-control" data-toggle="datetimepicker" data-target="#shiftStartdatetimepicker"/>
  </div>

  <div class="form-group">
    <label>{{ __('general.end_date') }}</label>
    <input type="text" required name="end_date" id="shiftEnddatetimepicker" class="form-control" data-toggle="datetimepicker" data-target="#shiftEnddatetimepicker"/>
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
    
    function init_start_date(){
      $('#shiftStartdatetimepicker').datetimepicker('destroy')
      $('#shiftStartdatetimepicker').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        useCurrent: false,
        // defaultDate: $('#shiftStartdatetimepicker').val() !== "" ? moment($('#shiftStartdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment('{{ !empty($jobs) ? $jobs->start_date->formatLocalized('%d-%m-%Y') : \Carbon\Carbon::now()->formatLocalized('%d-%m-%Y') }}', 'DD-MM-YYYY HH:mm'),
        // minDate: moment().startOf('day'),
        // maxDate: $('#shiftEnddatetimepicker').val() !== "" ? moment($('#shiftEnddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment().add(3, 'd'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#shiftStartdatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_end_date()
        }
      })
    }

    function init_end_date(){
      
      $('#shiftEnddatetimepicker').datetimepicker('destroy')
      $('#shiftEnddatetimepicker').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        useCurrent: false,
        // defaultDate: $('#shiftEnddatetimepicker').val() !== "" ? moment($('#shiftEnddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment('{{ !empty($jobs) ? $jobs->end_date->formatLocalized('%d-%m-%Y') : \Carbon\Carbon::now()->addDays(1)->formatLocalized('%d-%m-%Y') }}', 'DD-MM-YYYY HH:mm'),
        // minDate: $('#shiftStartdatetimepicker').val() !== "" ? moment($('#shiftStartdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment().startOf('day'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#shiftEnddatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_start_date()
        }
      })
    }
    
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
    }

    function reset(){
      is_publish = 1
      $('#radio-publish').prop('checked', true)
      url_image = '{{ $url_asset."/image/no_image_available.jpeg" }}'
      $('#image_showimage').attr('src', url_image)
    }

    function submit_banner(){
      var start_date = moment($('#shiftStartdatetimepicker').val(), 'DD-MM-YYYY HH:mm')
      var end_date = moment($('#shiftEnddatetimepicker').val(), 'DD-MM-YYYY HH:mm')
      
      if(start_date.isAfter(end_date))
        notify_user("Start date is after End date")
      else if(url_image === "")
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