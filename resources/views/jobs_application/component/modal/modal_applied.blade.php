<div class="modal fade" id="editApplied" tabindex="-1" aria-labelledby="editAppliedLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ url('/jobs/applied') }}" id="formEditApplied" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="applied_id" name="id"/>
        <input type="hidden" id="jobs_application_id" name="jobs_application_id" value="{{ !empty($jobs_application) ? $jobs_application->id : '' }}"/>
        <input type="hidden" id="jobs_id" name="jobs_id" value="{{ !empty($jobs_application) ? $jobs_application->jobs->id : $jobs->id }}"/>
        <input type="hidden" id="user_id" name="user_id"/>
        <input type="hidden" id="applied_from" name="from"/>

        <div class="modal-header">
          <h5 class="modal-title" id="editAppliedLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.brief_schedule') }}</label>
            <input type="text" class="form-control" name="brief_schedule" id="applied_brief_schedule" data-target="#applied_brief_schedule" data-toggle="datetimepicker" value="">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.brief_location') }}</label>
            <input type="text" class="form-control" name="brief_location" id="applied_brief_location" aria-describedby="emailHelp">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.work_schedule') }}</label>
            <input type="text" class="form-control" name="work_schedule" id="applied_work_schedule" data-target="#applied_work_schedule" data-toggle="datetimepicker" value="">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.work_location') }}</label>
            <input type="text" class="form-control" name="work_location" id="applied_work_location" aria-describedby="emailHelp">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.close') }}</button>
          <button class="btn btn-primary">{{ __('general.submit') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var applied_datatable = null
  function set_applied_data_modal(data = null){
    @if(!empty($jobs_applied))
      $('#applied_id').val('{{ $jobs_applied->id }}')
      $('#applied_user_name').val('{{ $jobs_applied->pic_name }}')
      $('#applied_user_phone').val('{{ $jobs_applied->pic_phone }}')
      $('#applied_brief_schedule').val('{{ $jobs_applied->brief_schedule->formatLocalized("%d-%m-%Y %H:%M") }}')
      $('#applied_brief_location').val('{{ $jobs_applied->brief_location }}')
      $('#applied_work_schedule').val('{{ $jobs_applied->work_schedule->formatLocalized("%d-%m-%Y %H:%M") }}')
      $('#applied_work_location').val('{{ $jobs_applied->work_location }}')
    @endif
    
    $('#editAppliedLabel').html(data != null ? '{{ __("general.edit_applied") }}' : '{{ __("general.set_applied") }}')
    $('#formEditApplied').attr('action', data != null ? '{{ url("/jobs/applied/edit") }}' : '{{ url("/jobs/applied") }}')
  }

  $(document).ready(function () {
    $('#applied_brief_schedule').datetimepicker({
      format: 'DD-MM-YYYY HH:mm',
      minDate: new Date(),
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar",
      }
    })
    $('#applied_work_schedule').datetimepicker({
      format: 'DD-MM-YYYY HH:mm',
      minDate: new Date(),
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar",
      }
    })

    $('#applied_user_phone').keyup(() => {
      $('#applied_user_phone').val(phone_validation($('#applied_user_phone').val()))
    })
  })
</script>
@endpush