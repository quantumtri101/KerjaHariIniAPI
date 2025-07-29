<div>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.interviewer_name') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_interview) ? $jobs_interview->interviewer_name : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.interviewer_phone') }}</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
              </div>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_interview) ? substr($jobs_interview->interviewer_phone, 3) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.schedule') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_interview) ? $jobs_interview->schedule->formatLocalized('%d %B %Y %H:%M') : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
  
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.type') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_interview) ? __('general.'.$jobs_interview->type) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
  
        @if(!empty($jobs_interview) && !empty($jobs_interview->zoom_url))
          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.online_meeting_url') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_interview->zoom_url }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>
        @endif
  
        @if(!empty($jobs_interview) && !empty($jobs_interview->location))
          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.location') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_interview->location }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="col-12 d-flex mt-3">
      <button type="button" class="btn btn-primary" id="edit_applied" data-toggle="modal" data-target="#editInterview" onclick="set_interview_data_modal('')">
        {{ __('general.edit_interview') }}
      </button>
    </div>
  </div>
</div>