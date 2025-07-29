<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_recommendation->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.user_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_recommendation->user->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      {{-- <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.city_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_recommendation->city->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.category_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs_recommendation->category->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div> --}}

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.range_salary') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="Rp. {{ number_format($jobs_recommendation->range_salary->min_salary, 0, ',', '.') }} - {{ number_format($jobs_recommendation->range_salary->max_salary, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>
</div>