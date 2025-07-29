@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.company'),
      __('company.edit'),
    ] : [
      __('general.company'),
      __('company.add'),
    ],
    "title" => Request::has('id') ? __('company.edit') : __('company.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('company.edit') : __('company.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/master/company/edit' : '/master/company') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.category') }}</label>
          <select name="category_id" required class="form-control">
            @foreach($arr_category as $category)
              <option value="{{ $category->id }}" {{ !empty($company) && !empty($compant->category) && $company->category->id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>{{ __('general.province') }}</label>
          <select name="province_id" id="province_id" required class="form-control">
            <option value="">{{ __('general.choose_province') }}</option>
            @foreach($arr_province as $province)
              <option value="{{ $province->id }}" {{ !empty($company) && !empty($company->city) && $company->city->province->id == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>{{ __('general.city') }}</label>
          <select name="city_id" id="city_id" required class="form-control">
            <option value="">{{ __('general.choose_city') }}</option>
            @foreach($arr_city as $city)
              <option value="{{ $city->id }}" {{ !empty($company) && !empty($company->city) && $company->city->id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>{{ __('general.name') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($company) ? $company->name : '' }}"/>
        </div>

        <div class="form-group">
          <label>{{ __('general.phone') }}</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">{{ __('general.code_area') }}</span>
            </div>
            <input type="text" required name="phone" id="phone" class="form-control" value="{{ !empty($company) ? substr($company->phone, 3) : '' }}"/>
          </div>
        </div>

        <div class="form-group">
          <label>{{ __('general.address') }}</label>
          <textarea required name="address" class="form-control">{{ !empty($company) ? $company->address : '' }}</textarea>
        </div>

        <div class="form-group">
          <label>{{ __('general.image') }}</label>
          @include('layout.upload_photo', [
            "column" => "file_name",
            "form_name" => "image",
            "data" => $company,
            "id" => "image",
            "url_image" => "/image/company",
          ])
        </div>

        <div class="form-group" >
          <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
          <button class="btn btn-primary" id="submit" onclick="">{{ __('general.submit') }}</button>
        </div>
      </form>
    </div>
  </div>

  @push('script')
    <script>
      async function get_city(){
        var response = await request('{{ url("/api/city/all") }}?is_publish=1&city_id=' + $('#province_id').val())
        if(response != null){
          if(response.status === "success"){
            var str = `
              <option value="">{{ __('general.choose_city') }}</option>
            `
            for(let city of response.data)
              str += `<option value="${city.id}">${city.name}</option>`
            // console.log(response.data)
            $('#city_id').html(str)
          }
        }
      }
      
      $(document).ready(() => {
        var type = '{{ !empty($company) ? 'edit' : 'add' }}'
        $('#phone').keyup(() => {
          $('#phone').val(phone_validation($('#phone').val()))
        })
        $('#province_id').change(() => {
          get_city()
        })
        $('#submit').click((e) => {
          if(type == 'edit')
            back_page(false)
        })
      })
    </script>
  @endpush
@endsection
