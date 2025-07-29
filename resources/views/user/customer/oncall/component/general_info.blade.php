<div class="row">
  <div class="col-12 col-lg-8">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_oncall->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_oncall->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.email') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_oncall->email }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_oncall->phone }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_oncall->gender == 1 ? __('general.male') : __('general.female') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      {{-- <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.salary_balance') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($customer_oncall->salary_balance, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div> --}}

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status_active') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($customer_oncall->is_active == 1 ? 'active' : 'inactive')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      @if(count($customer_oncall->general_quiz_result) > 0)
        <div class="col-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.general_quiz_result') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $customer_oncall->general_quiz_result[0]->score }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      @endif
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="form-group">
      <label>{{ __('general.image') }}</label>
      <div>
        <img src="{{ !empty($customer_oncall->file_name) ? url('/image/user?file_name='.$customer_oncall->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}" width="49%"/>
      </div>
    </div>
  </div>

  @if(empty($jobs_application))
    <div class="col-12 d-flex mt-3">
      <a class="btn btn-primary" onclick="save_current_page('{{ __('customer_oncall.detail') }}')" href="{{ url('/user/customer/oncall/action?id='.$customer_oncall->id) }}">{{ __('general.edit') }}</a>

      <a class="btn btn-primary ml-3" href="{{ url('/auth/reset-password?id='.$customer_oncall->id) }}">{{ __('general.reset_password') }}</a>

      <a class="btn btn-primary ml-3" href="{{ url('/auth/change-active?id='.$customer_oncall->id) }}">{{ __('general.'.($customer_oncall->is_active == 1 ? 'set_inactive' : 'set_active')) }}</a>
    </div>
  @endif
</div>