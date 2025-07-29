<div class="row">
  <div class="col-12 col-lg-8">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_regular->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_regular->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.email') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_regular->email }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_regular->phone }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_regular->gender == 1 ? __('general.male') : __('general.female') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      {{-- <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.salary_balance') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($customer_regular->salary_balance, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div> --}}

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status_active') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($customer_regular->is_active == 1 ? 'active' : 'inactive')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.contract_start_date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ !empty($customer_regular->contract_start_date) ? $customer_regular->contract_start_date->formatLocalized('%d %B %Y') : '-' }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.contract_duration') }}</label>
          <div class="input-group">
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($customer_regular->contract_duration, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon1">{{ __('general.year') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id_no') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_regular->id_no }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="form-group">
      <label>{{ __('general.image') }}</label>
      <div>
        <img src="{{ !empty($customer_regular->file_name) ? url('/image/user?file_name='.$customer_regular->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}" width="49%"/>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.covid_vaccine') }}</label>
      <div>
        <img src="{{ !empty($customer_regular->vaccine_covid_file_name) ? url('/image/user?file_name='.$customer_regular->vaccine_covid_file_name) : $url_asset.'/image/no_image_available.jpeg' }}" width="49%"/>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.cv') }}</label>
      <div>
        <img src="{{ !empty($customer_regular->cv_file_name) ? url('/image/user?file_name='.$customer_regular->cv_file_name) : $url_asset.'/image/no_image_available.jpeg' }}" width="49%"/>
      </div>
    </div>
  </div>

  @if(empty($jobs_application))
    <div class="col-12 d-flex mt-3">
      <a class="btn btn-primary" onclick="save_current_page('{{ __('customer_regular.detail') }}')" href="{{ url('/user/customer/regular/action?id='.$customer_regular->id) }}">{{ __('general.edit') }}</a>

      <a class="btn btn-primary ml-3" href="{{ url('/auth/reset-password?id='.$customer_regular->id) }}">{{ __('general.reset_password') }}</a>

      <a class="btn btn-primary ml-3" href="{{ url('/auth/change-active?id='.$customer_regular->id) }}">{{ __('general.'.($customer_regular->is_active == 1 ? 'set_inactive' : 'set_active')) }}</a>
    </div>
  @endif
</div>