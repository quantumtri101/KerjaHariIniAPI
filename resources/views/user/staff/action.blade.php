@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.staff'),
      __('staff.detail'),
      __('staff.edit'),
    ] : [
      __('general.staff'),
      __('staff.add'),
    ],
    "title" => Request::has('id') ? __('staff.edit') : __('staff.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('staff.edit') : __('staff.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/user/staff/edit' : '/user/staff') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="row">
          <div class="col-12 col-lg-4">
            @include('layout.upload_photo', [
              'data' => $user,
              'url_image' => '/image/user',
            ])
          </div>

          <div class="col-12 col-lg-8">
            <div class="form-group">
              <label>{{ __('general.status_active') }}</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_active" value="1" id="defaultCheckActive" required {{ !empty($user) && $user->is_active == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="defaultCheckActive">
                  {{ __('general.active') }}
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_active" value="0" id="defaultCheckInactive" required {{ !empty($user) && $user->is_active == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="defaultCheckInactive">
                  {{ __('general.inactive') }}
                </label>
              </div>
            </div>

            <div class="form-group">
              <label>{{ __('general.company') }}</label>
              <select required name="company_id" {{ !empty(Auth::user()->company) ? 'disabled' : '' }} class="form-control">
                @foreach($arr_company as $company)
                  <option value="{{ $company->id }}" {{ (!empty(Auth::user()->company) && Auth::user()->company->id == $company->id) || (empty(Auth::user()->company) && !empty($user) && $user->company->id == $company->id) ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>{{ __('general.company_position') }}</label>
              <select required name="company_position_id" class="form-control">
                @foreach($arr_company_position as $company_position)
                  <option value="{{ $company_position->id }}" {{ !empty($user) && $user->company_position->id == $company_position->id ? 'selected' : '' }}>{{ $company_position->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>{{ __('general.sub_category') }}</label>
              <select required name="sub_category_id" class="form-control">
                @foreach($arr_sub_category as $sub_category)
                  <option value="{{ $sub_category->id }}" {{ !empty($user) && !empty($user->sub_category) && $user->sub_category->id == $sub_category->id ? 'selected' : '' }}>{{ $sub_category->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>{{ __('general.name') }}</label>
              <input type="text" required name="name" class="form-control" value="{{ !empty($user) ? $user->name : '' }}"/>
            </div>

            <div class="form-group">
              <label>{{ __('general.email') }}</label>
              <input type="email" required name="email" class="form-control" value="{{ !empty($user) ? $user->email : '' }}"/>
            </div>

            <div class="form-group">
              <label>{{ __('general.phone') }}</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
                </div>
                <input type="text" required name="phone" id="phone" class="form-control" value="{{ !empty($user) ? $user->phone : '' }}"/>
              </div>
            </div>

            <div class="form-group">
              <label>{{ __('general.gender') }}</label>
              <select required name="gender" class="form-control">
                <option value="1" {{ !empty($user) && $user->gender == 1 ? 'selected' : '' }}>{{ __('general.male') }}</option>
                <option value="0" {{ !empty($user) && $user->gender == 0 ? 'selected' : '' }}>{{ __('general.female') }}</option>
              </select>
            </div>
          </div>

          <div class="col-12 mt-3">
            <div class="form-group" >
              <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
              <button class="btn btn-primary" id="submit" onclick="back_page(false)">{{ __('general.submit') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('script')
    <script>

      $(document).ready(() => {

        $('#phone').keyup(() => {
          $('#phone').val(phone_validation($('#phone').val()))
        })

        // init_birth_date()
      })
    </script>
  @endpush
@endsection
