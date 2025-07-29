<div class="row">
  <div class="col-12 col-lg-8">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.email') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->email }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->phone }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->gender == 1 ? __('general.male') : __('general.female') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status_active') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($staff->is_active == 1 ? 'active' : 'inactive')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      @if(!empty($staff->company))
        <div class="col-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.company') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->company->name }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      @endif

      @if(!empty($staff->sub_category))
        <div class="col-6">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.sub_category') }}</label>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $staff->sub_category->name }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      @endif
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="form-group">
      <label>{{ __('general.image') }}</label>
      <div>
        <img src="{{ !empty($staff->file_name) ? url('/image/user?file_name='.$staff->file_name) : $url_asset.'/image/blank_profile_picture.webp' }}" width="49%"/>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    <a class="btn btn-primary" onclick="save_current_page('{{ __('staff.detail') }}')" href="{{ url('/user/staff/action?id='.$staff->id) }}">{{ __('general.edit') }}</a>

    <a class="btn btn-primary ml-3" href="{{ url('/auth/reset-password?id='.$staff->id) }}">{{ __('general.reset_password') }}</a>

    <a class="btn btn-primary ml-3" href="{{ url('/auth/change-active?id='.$staff->id) }}">{{ __('general.'.($staff->is_active == 1 ? 'set_inactive' : 'set_active')) }}</a>
  </div>
</div>