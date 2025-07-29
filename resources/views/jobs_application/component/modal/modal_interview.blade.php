<div class="modal fade" id="editInterview" tabindex="-1" aria-labelledby="editInterviewLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ url('/jobs/interview') }}" id="formEditInterview" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="interview_id" name="id"/>
        <input type="hidden" id="jobs_application_id" name="jobs_application_id" value="{{ $jobs_application->id }}"/>

        <div class="modal-header">
          <h5 class="modal-title" id="editInterviewLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.schedule') }}</label>
            <input type="text" class="form-control" name="schedule" id="interview_schedule" data-target="#interview_schedule" data-toggle="datetimepicker" value="">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.type') }}</label>
            <select name="type" id="interview_type" class="form-control">
              <option value="">{{ __('general.choose_type') }}</option>
              <option value="online">{{ __('general.online') }}</option>
              <option value="offline">{{ __('general.offline') }}</option>
            </select>
          </div>

          <div class="form-group d-none" id="online_container">
            <label for="exampleInputEmail1">{{ __('general.online_meeting_url') }}</label>
            <input type="text" class="form-control" name="zoom_url" id="interview_zoom_url" data-target="#interview_zoom_url" data-toggle="datetimepicker" value="">
          </div>

          <div class="form-group d-none" id="offline_container">
            <label for="exampleInputEmail1">{{ __('general.location') }}</label>
            <input type="text" class="form-control" name="location" id="interview_location" aria-describedby="emailHelp">
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
  var interview_datatable = null
  function set_interview_data_modal(data = null){
    @if(!empty($jobs_interview))
      $('#interview_id').val('{{ $jobs_interview->id }}')
      $('#interview_interviewer_name').val('{{ $jobs_interview->interviewer_name }}')
      $('#interview_interviewer_phone').val('{{ $jobs_interview->interviewer_phone }}')
      $('#interview_schedule').val('{{ $jobs_interview->schedule->formatLocalized("%d-%m-%Y %H:%M") }}')
      $('#interview_type').val('{{ $jobs_interview->type }}')
      $('#interview_zoom_url').val('{{ $jobs_interview->zoom_url }}')
      $('#interview_location').val('{{ $jobs_interview->location }}')
      if($('#interview_type').val() == "online"){
        $('#online_container').removeClass('d-none')
        $('#offline_container').addClass('d-none')
      }
      else if($('#interview_type').val() == "offline"){
        $('#online_container').addClass('d-none')
        $('#offline_container').removeClass('d-none')
      }
    @endif
    
    $('#editInterviewLabel').html(data != null ? '{{ __("general.edit_interview") }}' : '{{ __("general.set_interview") }}')
    $('#formEditInterview').attr('action', data != null ? '{{ url("/jobs/interview/edit") }}' : '{{ url("/jobs/interview") }}')
  }

  $(document).ready(function () {
    $('#interview_schedule').datetimepicker({
      format: 'DD-MM-YYYY HH:mm',
      minDate: new Date(),
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar",
      }
    })
    $('#interview_type').change(() => {
      if($('#interview_type').val() == "online"){
        $('#online_container').removeClass('d-none')
        $('#offline_container').addClass('d-none')
      }
      else if($('#interview_type').val() == "offline"){
        $('#online_container').addClass('d-none')
        $('#offline_container').removeClass('d-none')
      }
    })

    $('#interview_interviewer_phone').keyup(() => {
      $('#interview_interviewer_phone').val(phone_validation($('#interview_interviewer_phone').val()))
    })
  })
</script>
@endpush