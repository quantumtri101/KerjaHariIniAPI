<div class="row">
  <div class="col-12 col-lg-8">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $company->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.city_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($company->city) ? $company->city->name : '-' }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.category_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($company->category) ? $company->category->name : '-' }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $company->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.phone') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $company->phone }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.address') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $company->address }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="form-group">
      <label for="exampleInputEmail1">{{ __('general.image') }}</label>
      <div>
        <img src="{{ url('/image/company?file_name='.$company->file_name) }}" width="50%"/>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    <a class="btn btn-primary" onclick="save_current_page('{{ __('company.detail') }}')" href="{{ url('/master/company/action?id='.$company->id) }}">{{ __('general.edit') }}</a>
  </div>
</div>