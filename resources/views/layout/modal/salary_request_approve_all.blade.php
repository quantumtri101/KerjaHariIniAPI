<div class="modal fade" id="salary_request_approve_all" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.salary_request_approve') }}</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/salary/approve/salary/all') }}">
          @csrf
          <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>
          <input type="hidden" name="is_approve_salary" value="requested"/>

          <div class="form-group">
            <label>{{ __('general.file') }}</label>
            @include('layout.upload_multiple_photo', [
              "isArrImageUsingID" => true,
              "column" => "file_name",
              "form_name" => "file[]",
              "accept" => "*/*",
              "id" => "salary_image_all",
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
