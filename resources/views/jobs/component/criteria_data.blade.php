<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->criteria) > 0 ? $jobs->criteria[0]->gender : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.education') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->criteria) > 0 ? $jobs->criteria[0]->education->name : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.age_range') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->criteria) > 0 ? $jobs->criteria[0]->min_age.' - '.$jobs->criteria[0]->max_age : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.has_pkwt') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->criteria) > 0 ? ($jobs->criteria[0]->has_pkwt == 1 ? __('general.yes') : __('general.no')) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.has_pkhl') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->criteria) > 0 ? ($jobs->criteria[0]->has_pkhl == 1 ? __('general.yes') : __('general.no')) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.is_working_same_company') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ count($jobs->criteria) > 0 ? ($jobs->criteria[0]->is_working_same_company == 1 ? __('general.yes') : __('general.no')) : __('general.not_available') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.working_area') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->working_area_str }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.other_criteria') }}</label>
          <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ count($jobs->criteria) > 0 ? $jobs->criteria[0]->other : __('general.not_available') }}</textarea>
        </div>
      </div>
    </div>
  </div>
</div>