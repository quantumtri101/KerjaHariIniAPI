<div>
  <div class="card">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">List Image</h5>
      @include('jobs.component.list_image')
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">List Qualification</h5>
      @include('jobs.component.list_qualification')
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">Criteria</h5>
      @include('jobs.component.criteria_data')
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">Briefing</h5>
      @include('jobs.component.briefing_data')
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">
      <h5 class="mb-0 text-gray-800 font-weight-bold">Interview</h5>
      @include('jobs.component.interview_data')
    </div>
  </div>
</div>