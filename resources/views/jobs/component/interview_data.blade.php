<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.interviewer_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->interview) > 0 ? $jobs->interview[0]->interviewer_name : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.interviewer_phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->interview) > 0 ? substr($jobs->interview[0]->interviewer_phone, 3) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.schedule') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->interview) > 0 ? $jobs->interview[0]->schedule->formatLocalized('%d %B %Y %H:%M') : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.type') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->interview) > 0 ? __('general.'.$jobs->interview[0]->type) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      @if(count($jobs->interview) > 0 && !empty($jobs->interview->zoom_url))
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.online_meeting_url') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->interview[0]->zoom_url }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      @endif

      @if(count($jobs->interview) > 0 && !empty($jobs->interview->location))
        <div class="col-12 col-lg-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.location') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->interview[0]->location }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      @endif

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.notes') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->interview) > 0 ? $jobs->interview[0]->notes : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>
</div>