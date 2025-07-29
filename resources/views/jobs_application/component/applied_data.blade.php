<div>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.person_in_charge_name') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_application) ? $jobs_applied->pic_name : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.person_in_charge_phone') }}</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
              </div>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_application) ? substr($jobs_applied->pic_phone, 3) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.brief_schedule') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_application) ? $jobs_applied->brief_schedule->formatLocalized('%d %B %Y') : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
  
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.brief_location') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_application) ? $jobs_applied->brief_location : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
  
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.work_schedule') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_application) ? $jobs_applied->work_schedule->formatLocalized('%d %B %Y') : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
  
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.work_location') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($jobs_application) ? $jobs_applied->work_location : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>
    </div>

    @if(!empty($jobs_application) && $jobs_application->status == "accepted")
      <div class="col-12 d-flex mt-3">
        <button type="button" class="btn btn-primary" id="edit_applied" data-toggle="modal" data-target="#editApplied" onclick="set_applied_data_modal('')">
          {{ __('general.edit_applied') }}
        </button>
      </div>
    @endif
  </div>
</div>