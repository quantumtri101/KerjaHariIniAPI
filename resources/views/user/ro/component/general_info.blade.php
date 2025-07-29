<div class="row">
  <div class="col-12 col-lg-8">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $ro->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.company_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $ro->company->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $ro->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.email') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $ro->email }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $ro->phone }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $ro->gender == 1 ? __('general.male') : __('general.female') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status_active') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($ro->is_active == 1 ? 'active' : 'inactive')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="form-group">
      <label>{{ __('general.image') }}</label>
      <div>
        <img src="{{ !empty($ro->file_name) ? url('/image/user?file_name='.$ro->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}" width="49%"/>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    <a class="btn btn-primary" onclick="save_current_page('{{ __('ro.detail') }}')" href="{{ url('/user/ro/action?id='.$ro->id) }}">{{ __('general.edit') }}</a>

    <a class="btn btn-primary ml-3" href="{{ url('/auth/reset-password?id='.$ro->id) }}">{{ __('general.reset_password') }}</a>

    <a class="btn btn-primary ml-3" href="{{ url('/auth/change-active?id='.$ro->id) }}">{{ __('general.'.($ro->is_active == 1 ? 'set_inactive' : 'set_active')) }}</a>
  </div>
</div>