<div class="modal fade" id="jobs_upload_document_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.jobs_upload_document') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/jobs/document') }}">
          @csrf
          <input type="hidden" name="jobs_id" id="jobs_document_jobs_id"/>

          <div class="form-group">
            <label>{{ __('general.image') }}</label>
            @include('layout.upload_multiple_photo', [
              "column" => "file_name",
              "form_name" => "file[]",
              "data" => $jobs,
              "accept" => "*/*",
              "id" => "jobs_document_image",
              "url_image" => "/image/jobs/document",
            ])
          </div>

          <div class="form-group">
            <button class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var jobs_upload_document_modal = new Vue({
      el: '#jobs_upload_document_modal',
      data: {
      },
    })
  </script>
@endpush
