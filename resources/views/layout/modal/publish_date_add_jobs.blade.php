<div class="modal fade" id="publish_date_add_jobs" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.publish_job') }}</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>{{ __('general.publish_start_date') }}</label>
          <div id="publishstartdatetimepickererror">
            <label >{{ __('general.datepicker_error') }}</label>
          </div>
          <input type="text" id="publishstartdatetimepicker" class="form-control" data-toggle="datetimepicker" data-target="#publishstartdatetimepicker" value=""/>
        </div>

        <div class="form-group">
          <label>{{ __('general.publish_end_date') }}</label>
          <div id="publishenddatetimepickererror">
            <label >{{ __('general.datepicker_error') }}</label>
          </div>
          <input type="text" id="publishenddatetimepicker" class="form-control" data-toggle="datetimepicker" data-target="#publishenddatetimepicker" value=""/>
        </div>

        <div>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="publish_date_action()">Submit</button>
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var publish_start_date_first_time = true
    var publish_end_date_first_time = true
    var publish_start_date_first_time1 = true
    var publish_end_date_first_time1 = true
    var start_date = moment()
    var end_date = moment()
    var publish_start_obj = null
    var publish_end_obj = null

    var shift_start_date = moment('{{ !empty($jobs) ? $jobs->shift[0]->start_date->formatLocalized("%d-%m-%Y %H:%i") : \Carbon\Carbon::now()->formatLocalized("%d-%m-%Y %H:%i") }}', 'DD-MM-YYYY HH:mm')
    var shift_end_date = moment('{{ !empty($jobs) ? $jobs->shift[0]->end_date->formatLocalized("%d-%m-%Y %H:%i") : \Carbon\Carbon::now()->addDays(1)->formatLocalized("%d-%m-%Y %H:%i") }}', 'DD-MM-YYYY HH:mm')
    var publish_date_jobs_id = ""
    
    function publish_date_action(){
      var publish_start_date = moment($('#publishstartdatetimepicker').val(), 'DD-MM-YYYY HH:mm')
      var publish_end_date = moment($('#publishenddatetimepicker').val(), 'DD-MM-YYYY HH:mm')

      if(publish_start_date.isAfter(publish_end_date))
        alert("Start date is after End date")
      else{
        back_page(false)
        $('#jobsForm').trigger('submit')
      }
    }

    var notify_auto_live_add_jobs = new Vue({
      el: '#notify_auto_live_add_jobs',
      data: {
      },
    })

    function publish_start_date(){
      if(!publish_start_date_first_time)
        $('#publishstartdatetimepicker').datetimepicker('destroy')
      publish_start_date_first_time = false
      $('#publishstartdatetimepickererror').addClass('d-none')
      $('#publishstartdatetimepicker').removeClass('d-none')
      
      try{
        $('#publishstartdatetimepicker').datetimepicker({
          format: 'DD-MM-YYYY HH:mm',
          useCurrent: false,
          // defaultDate: moment().isAfter(shift_start_date) ? shift_start_date : moment(),
          minDate: false,
          maxDate: false,
          icons: {
            time: 'fa-solid fa-clock',
            date: 'fa-solid fa-calendar',
          },
        })
      } catch(e){
        console.log(e)
        $('#publishstartdatetimepickererror').removeClass('d-none')
        $('#publishstartdatetimepicker').addClass('d-none')
      }
      
      $('#publishstartdatetimepicker').on("show.datetimepicker", ({date, oldDate}) => {
        $('#publishenddatetimepicker').datetimepicker('hide')
      })
      $('#publishstartdatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        $('#publish_start_date').val(moment(date).format('DD/MM/YYYY HH:mm'))
      //   if(oldDate != null){
      //     publish_start_date_first_time1 = false
      //     publish_end_date_first_time1 = false
          
      //     if(publish_start_date_first_time1)
      //       $('#publishenddatetimepicker').val((moment(date).isSame(shift_start_date, 'days') ? moment(date) : moment(date).add(1, 'd')).format('DD-MM-YYYY HH:mm'))
          
      //     $('#publishstartdatetimepicker').datetimepicker('hide')

      //     // console.log('start', date)
      //     start_date = moment(date)
      //     publish_start_date()
      //     publish_end_date()
      //   }
      //   return false
      })

      $('#publish_start_date').val(moment($('#publishstartdatetimepicker').val(), 'DD-MM-YYYY HH:mm').format('DD/MM/YYYY HH:mm'))
    }

    function publish_end_date(){
      if(!publish_end_date_first_time)
        $('#publishenddatetimepicker').datetimepicker('destroy')
      publish_end_date_first_time = false
      $('#publishenddatetimepickererror').addClass('d-none')
      $('#publishenddatetimepicker').removeClass('d-none')
      
      try{
        $('#publishenddatetimepicker').datetimepicker({
          format: 'DD-MM-YYYY HH:mm',
          useCurrent: false,
          // defaultDate: moment().isAfter(shift_start_date) ? shift_start_date : moment(),
          minDate: false,
          maxDate: false,
          icons: {
            time: 'fa-solid fa-clock',
            date: 'fa-solid fa-calendar',
          },
        })
      } catch(e){
        console.log(e)
        $('#publishenddatetimepickererror').removeClass('d-none')
        $('#publishenddatetimepicker').addClass('d-none')
      }
      
      $('#publishenddatetimepicker').on("show.datetimepicker", ({date, oldDate}) => {
        $('#publishstartdatetimepicker').datetimepicker('hide')
      })
      $('#publishenddatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        $('#publish_end_date').val(moment(date).format('DD/MM/YYYY HH:mm'))
      //   if(oldDate != null){
      //     publish_start_date_first_time1 = false
      //     publish_end_date_first_time1 = false
          
      //     if(publish_start_date_first_time1)
      //       $('#publishstartdatetimepicker').val((moment(date).isSame(moment(), 'days') ? moment(date) : moment(date).subtract(1, 'd')).format('DD-MM-YYYY HH:mm'))
          
      //     $('#publishenddatetimepicker').datetimepicker('hide')

      //     end_date = moment(date)
      //     publish_start_date()
      //     publish_end_date()
      //   }
      //   return false
      })

      $('#publish_end_date').val(moment($('#publishenddatetimepicker').val(), 'DD-MM-YYYY HH:mm').format('DD/MM/YYYY HH:mm'))
    }
  </script>
@endpush

@push('afterScript')
@endpush
