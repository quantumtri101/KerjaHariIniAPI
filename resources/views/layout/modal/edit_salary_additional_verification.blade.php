<div class="modal fade" id="edit_salary_additional_verification" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.edit_salary_additional_verification') }}</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/salary/edit/additional-salary') }}">
          @csrf
          <input type="hidden" name="jobs_shift_id" id="salary_additional_jobs_shift_id"/>

          <div class="form-group">
            <label>{{ __('general.user') }}</label>
            <select id="user_id_salary_additional_verification" name="jobs_application_id" class="form-control"></select>
          </div>

          <div class="form-group">
            <label>{{ __('general.additional_salary') }}</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
              </div>
              <input type="text" id="additional_salary_salary_additional_verification" name="additional_salary" class="form-control"/>
            </div>
          </div>

          <div class="form-group">
            <label>{{ __('general.file') }}</label>
            @include('layout.upload_multiple_photo', [
              "column" => "file_name",
              "form_name" => "file[]",
              "accept" => "*/*",
              "id" => "additional_salary_image",
              "url_image" => "/image/check-log/document",
            ])
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
      $('#additional_salary_salary_additional_verification').keyup(() => {
        $('#additional_salary_salary_additional_verification').val(to_currency_format($('#additional_salary_salary_additional_verification').val()))
      })

      $('#user_id_salary_additional_verification').select2({
        dropdownParent: $('#edit_salary_additional_verification'),
        ajax: {
          url: `{{ url('api/jobs/application') }}?arr_status=["working","done"]&arr_is_approve_additional_salary=["not_requested"]&jobs_id={{ $jobs_shift->jobs->id }}`,
          dataType: 'json',
          accept: 'application/json',
          data: function (params) {
            var query = {
              search: params.term
            }

            return query;
          },
          processResults: function (data) {
            return {
              results: data.data
            };
          }
        }
      });
    })
  </script>
@endpush
