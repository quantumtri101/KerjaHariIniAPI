<div class="modal fade" id="jobs_upload_pkwt_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.jobs_upload_pkwt') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/jobs/application/upload/pkwt') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" id="jobs_pkwt_jobs_application_id"/>

          <div class="form-group">
            <label>{{ __('general.image') }}</label>
            @include('layout.upload_photo', [
              "column" => "file_name",
              "form_name" => "file",
              "data" => $jobs,
              "accept" => "*/*",
              "id" => "jobs_pkwt_image",
              "url_image" => "/image/jobs/pkwt",
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
    var jobs_upload_pkwt_modal = new Vue({
      el: '#jobs_upload_pkwt_modal',
      data: {
      },
    })
  </script>
@endpush
