<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.person_in_charge_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->briefing) > 0 ? $jobs->briefing[0]->pic_name : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.person_in_charge_phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->briefing) > 0 ? substr($jobs->briefing[0]->pic_phone, 3) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.brief_schedule') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->briefing) > 0 ? $jobs->briefing[0]->schedule->formatLocalized('%d %B %Y') : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.brief_location') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->briefing) > 0 ? $jobs->briefing[0]->location : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.notes') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->briefing) > 0 ? $jobs->briefing[0]->notes : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>
</div>