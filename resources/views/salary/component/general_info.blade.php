<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->jobs->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->jobs->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.sub_category_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->jobs->sub_category->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.event_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->jobs->event->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.num_people_required') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->jobs->num_people_required }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.start_date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->start_date->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.end_date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_shift->end_date->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>
</div>