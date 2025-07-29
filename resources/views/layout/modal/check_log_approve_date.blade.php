<div class="modal fade" id="check_log_approve_date_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.check_log_approve') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/jobs/check-log/approve') }}">
          @csrf
          <input type="hidden" name="jobs_application_id" id="check_log_approve_date_jobs_application_id"/>
          

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.check_in_date') }}</label>
            <input type="text" name="check_in" id="checkintimepicker" class="form-control" data-toggle="datetimepicker" class="form-control">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.check_out_date') }}</label>
            <input type="text" name="check_out" id="checkouttimepicker" class="form-control" data-toggle="datetimepicker" class="form-control">
          </div>

          <div class="form-group">
            <button class="btn btn-primary">Approve</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var isFirstTimeCheckIn = true
    var isFirstTimeCheckOut = true

    function init_check_log_start_date(){
      if(!isFirstTimeCheckIn)
        $('#checkintimepicker').datetimepicker('destroy')
      $('#checkintimepicker').datetimepicker({
        format: 'HH:mm',
        useCurrent: false,
        defaultDate: moment($('#checkintimepicker').val(), 'HH:mm'),
        minDate: moment().startOf('day'),
        maxDate: moment($('#checkouttimepicker').val(), 'HH:mm'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#checkintimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_check_log_end_date()
        }
      })
      isFirstTimeCheckIn = false
    }

    function init_check_log_end_date(){
      if(!isFirstTimeCheckOut)
        $('#checkouttimepicker').datetimepicker('destroy')
      $('#checkouttimepicker').datetimepicker({
        format: 'HH:mm',
        useCurrent: false,
        defaultDate: moment($('#checkouttimepicker').val(), 'HH:mm'),
        minDate: moment($('#checkintimepicker').val(), 'HH:mm'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#checkouttimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_check_log_start_date()
        }
      })
      isFirstTimeCheckOut = false
    }
  </script>
@endpush
