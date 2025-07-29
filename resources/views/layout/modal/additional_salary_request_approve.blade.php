<div class="modal fade" id="additional_salary_request_approve" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.additional_salary_request_approve') }}</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/salary/approve/salary') }}" class="d-inline-block">
          @csrf
          <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>
          <input type="hidden" name="user_id" id="additional_salary_request_approve_user_id"/>
          <input type="hidden" name="is_approve_salary" value="requested"/>

          <div class="form-group">
            <label>{{ __('general.image') }}</label>
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
      
    })
  </script>
@endpush
