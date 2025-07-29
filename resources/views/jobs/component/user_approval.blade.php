<div>
  <div class="card">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">Detail Approval</h5>
      @include('jobs.component.list_approve')

      @if(Auth::user()->type->name == "staff" && $jobs->status_approve_data == "not_yet_approved" && $jobs->before_allow_edit)
        {{-- <form method="post" action="{{ url('/jobs/approve/change-approve') }}" class="d-inline-block ml-3">
          @csrf
          <input type="hidden" name="jobs_id" value="{{ $jobs->id }}"/>
          <input type="hidden" name="status_approve" value="approved"/>
          <button class="btn btn-primary">Approve</button>
        </form> --}}

        <a class="btn btn-primary" href="#!" onclick="showUploadDocumentModal('{{ $jobs->id }}')">Approve</a>
        <a class="btn btn-danger ml-3" href="#!" onclick="showDeclineModal('{{ $jobs->id }}')">Decline</a>

        @include('layout.modal.jobs_upload_document')
        @include('layout.modal.jobs_decline')
      @endif
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">List Approve Check Log</h5>
      @include('jobs.component.list_approve_check_log')
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">List Approve Salary</h5>
      @include('jobs.component.list_approve_salary')
    </div>
  </div>


</div>