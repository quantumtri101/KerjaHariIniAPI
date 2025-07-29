<div class="modal fade" id="check_log_manual" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.manual_check_log') }}</h5>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ url('/check-log') }}">
          @csrf
          <input type="hidden" name="jobs_id" value="{{ $jobs_shift->jobs->id }}"/>
          <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>

          <div class="form-group">
            <label>{{ __('general.customer') }}</label>
            <select class="form-control select2" name="user_id" id="user_id">
              <option value="">{{ __('general.choose_customer') }}</option>
              @foreach($arr_application as $jobs_application)
                <option value="{{ $jobs_application->user->id }}">{{ $jobs_application->user->id.' - '.$jobs_application->user->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>{{ __('general.type') }}</label>
            <select class="form-control" name="type" id="type">
              <option value="check_in">{{ __('general.check_in') }}</option>
              <option value="check_out">{{ __('general.check_out') }}</option>
            </select>
          </div>

          <div class="form-group">
            <label>{{ __('general.date') }}</label>
            <input type="text" id="datetimepicker" class="form-control" name="date" data-toggle="datetimepicker" data-target="#datetimepicker" value=""/>
          </div>

          <div>
            <button class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var firstTime = true
    var type = "check_in"
    function date_action(){
      $('#jobsForm').trigger('submit')
    }

    function init_datetimepicker(){
      if(!firstTime)
        $('#datetimepicker').datetimepicker('destroy')

      var arr = {
        format: 'DD-MM-YYYY HH:mm',
        minDate: moment('{{ $jobs_shift->start_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      }
      if(type == "check_in")
        arr.maxDate = moment('{{ $jobs_shift->end_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')
      

      $('#datetimepicker').datetimepicker(arr)
      $('#datetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          $('#date').val(moment(date).format('DD/MM/YYYY'))
        }
      })
      firstTime = false
    }

    $(document).ready(function() {
      $('#user_id').select2({
        dropdownParent: $('#check_log_manual')
      })
      $('#type').change(function () {
        type = $(this).val()
        init_datetimepicker()
      })
      init_datetimepicker()
    })
  </script>
@endpush
