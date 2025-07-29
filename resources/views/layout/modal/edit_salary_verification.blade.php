<div class="modal fade" id="edit_salary_verification" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.edit_salary_verification') }}</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/salary/edit/salary') }}">
          @csrf
          <input type="hidden" name="jobs_shift_id" id="salary_jobs_shift_id"/>
          <input type="hidden" name="user_id" id="salary_user_id"/>

          <div class="form-group">
            <label>{{ __('general.salary') }}</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
              </div>
              <input type="text" id="salary_salary_verification" name="salary" class="form-control"/>
            </div>
          </div>

          <div>
            <button type="submit" class="btn btn-primary" >Submit</button>
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>

    $(document).ready(function() {
      $('#salary_salary_verification').keyup(() => {
        $('#salary_salary_verification').val(to_currency_format($('#salary_salary_verification').val()))
      })
    })
  </script>
@endpush
