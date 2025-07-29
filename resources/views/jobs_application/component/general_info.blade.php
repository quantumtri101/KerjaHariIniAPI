<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_application->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.user_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_application->user->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      @if(!empty($jobs_application->general_quiz_result))
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.general_quiz_score') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_application->general_quiz_result->score }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      @endif

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.first_question') }}</label>
          <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $jobs_application->first_question }}</textarea>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.content') }}</label>
          <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $jobs_application->content }}</textarea>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.$jobs_application->status) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.general_quiz_score') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_application->general_quiz_score }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    @if($jobs_application->is_approve_corp == 0)
      @if($jobs_application->status == "wait")
        <button type="button" class="btn btn-primary" id="add_interview" data-toggle="modal" data-target="#editInterview" onclick="set_interview_data_modal()">
          {{ __('general.set_interview') }}
        </button>
      @elseif($jobs_application->status == "interview")
        <button type="button" class="btn btn-primary" id="add_applied" data-toggle="modal" data-target="#editApplied" onclick="set_applied()">
          {{ __('general.set_applied') }}
        </button>
      @elseif($jobs_application->status == "accepted" || $jobs_application->status == "working")
        <form method="post" action="{{ url('/jobs/application/change-status') }}">
          @csrf
          <input type="hidden" name="id" value="{{ $jobs_application->id }}"/>
          <input type="hidden" name="status" value="{{ $jobs_application->status == "accepted" ? 'working' : 'done' }}"/>
          <button class="btn btn-primary">{{ __('general.'.($jobs_application->status == "accepted" ? 'set_working' : 'set_done')) }}</button>
        </form>
      @endif
    @endif

    @if(($jobs_application->status == "wait" || $jobs_application->status == "interview") && $jobs_application->is_approve_corp == 0)
      <a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/jobs/application/delete?id='.$jobs_application->id) }}')">Decline</a>
    @endif
  </div>
</div>

@push('script')
<script type="text/javascript">
  var applied_datatable = null
  function set_applied(data = null){
    $('#applied_from').val('user')
    set_applied_data_modal()
  }
</script>
@endpush