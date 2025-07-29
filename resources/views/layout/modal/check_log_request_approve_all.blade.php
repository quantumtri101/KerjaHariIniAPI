<div class="modal fade" id="check_log_upload_all" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.check_log_upload') }}</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/check-log/requested/all') }}">
          @csrf
          <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>

          <div class="form-group">
            <label>{{ __('general.file') }}</label>
            @include('layout.upload_multiple_photo', [
              "column" => "file_name",
              "form_name" => "file[]",
              "accept" => "*/*",
              "id" => "check_log_document_image_all",
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
  </script>
@endpush
