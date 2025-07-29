<div class="modal fade" id="jobs_decline_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.jobs_decline') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/jobs/approve/change-approve') }}">
          @csrf
          <input type="hidden" name="jobs_id" id="jobs_decline_jobs_id"/>
          <input type="hidden" name="status_approve" value="declined"/>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.decline_reason') }}</label>
            <textarea class="form-control" name="decline_reason" id="decline_reason"></textarea>
          </div>

          <div class="form-group">
            <button class="btn btn-primary" id="decline_button">Decline</button>
            <button type="button" class="btn btn-secondary" onclick="jobsDeclineModalClose()" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    function jobsDeclineModalClose(){
      $('#decline_reason').attr('disabled', false)
      $('#decline_button').removeClass('d-none')
      $('#decline_reason').val('')
    }
    var jobs_decline_modal = new Vue({
      el: '#jobs_decline_modal',
      data: {
      },
    })
  </script>
@endpush
