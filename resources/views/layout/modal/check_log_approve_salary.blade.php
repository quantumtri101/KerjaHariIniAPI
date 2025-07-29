<div class="modal fade" id="check_log_approve_salary_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <input type="hidden" name="jobs_application_id" id="check_log_approve_salary_jobs_application_id"/>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.salary') }}</label>
            <input type="text" name="salary" id="check_log_approve_salary_salary" class="form-control">
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
    $(document).ready(() => {
      $('#check_log_approve_salary_salary').keyup(() => {
        $('#check_log_approve_salary_salary').val(to_currency_format($('#check_log_approve_salary_salary').val()))
      })
    })
  </script>
@endpush
