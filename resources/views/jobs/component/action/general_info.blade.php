<div class="row">
  <div class="col-12">
    <div class="form-group d-none">
      <label>{{ __('general.status_publish') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="is_publish" value="{{ $jobs->is_publish }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_publish" value="1" {{ !empty($jobs) && $jobs->is_publish == 1 ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-publish">
        <label class="form-check-label" for="radio-publish">
          {{ __('general.publish') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_publish" value="0" {{ !empty($jobs) && $jobs->is_publish == 0 ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-not_publish">
        <label class="form-check-label" for="radio-not_publish">
          {{ __('general.not_publish') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.status_urgent') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="is_urgent" value="{{ $jobs->is_urgent }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_urgent" value="1" {{ !empty($jobs) && $jobs->is_urgent == 1 ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-urgent">
        <label class="form-check-label" for="radio-urgent">
          {{ __('general.urgent') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="is_urgent" value="0" {{ !empty($jobs) && $jobs->is_urgent == 0 ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-not_urgent">
        <label class="form-check-label" for="radio-not_urgent">
          {{ __('general.not_urgent') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.type') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="staff_type" value="{{ $jobs->staff_type }}" />
      @endif
      <div class="form-check">
        <input class="form-check-input" type="radio" name="staff_type" value="closed" {{ !empty($jobs) && $jobs->staff_type == "closed" ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-closed">
        <label class="form-check-label" for="radio-closed">
          {{ __('general.closed') }}
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="staff_type" value="open" {{ !empty($jobs) && $jobs->staff_type == "open" ? 'checked' : '' }} {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="radio-open">
        <label class="form-check-label" for="radio-open">
          {{ __('general.open') }}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.province') }}</label>
      <select name="province_id" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="province_id" class="form-control">
        <option value="">{{ __('general.choose_province') }}</option>
        @foreach($arr_province as $province)
          <option value="{{ $province->id }}" {{ !empty($jobs) && $province->id == $jobs->city->province->id ? 'selected' : '' }}>{{ $province->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>{{ __('general.city') }}</label>
      <select name="city_id" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="city_id" class="form-control">
        <option value="">{{ __('general.choose_city') }}</option>
        @foreach($arr_city as $city)
          <option value="{{ $city->id }}" {{ !empty($jobs) && $city->id == $jobs->city->id ? 'selected' : '' }}>{{ $city->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>{{ __('general.event') }}</label>
      <select name="event_id" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="event_id" class="form-control">
        <option value="">{{ __('general.choose_event') }}</option>
        @foreach($arr_event as $event)
          <option value="{{ $event->id }}" {{ !empty($jobs) && $event->id == $jobs->event->id ? 'selected' : '' }}>{{ $event->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group d-none">
      <label>{{ __('general.department') }}</label>
      <select name="company_position_id" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="department_id" class="form-control">
        <option value="">{{ __('general.choose_department') }}</option>
        @foreach($arr_company_position as $company_position)
          <option value="{{ $company_position->id }}" {{ !empty($jobs) && !empty($jobs->company_position) && $company_position->id == $jobs->company_position->id ? 'selected' : '' }}>{{ $company_position->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>{{ __('general.category') }}</label>
      <select name="category_id" {{ ((!empty($jobs) && $jobs->allow_edit) || empty($jobs)) && empty(Auth::user()->company) ? '' : 'readonly' }} id="category_id" class="form-control">
        <option value="">{{ __('general.choose_category') }}</option>
        @foreach($arr_category as $category)
          <option value="{{ $category->id }}" {{ (!empty(Auth::user()->company) && Auth::user()->company->category->id == $category->id) || (empty(Auth::user()->company) && !empty($jobs) && $category->id == $jobs->sub_category->category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>{{ __('general.sub_category') }}</label>
      <select name="sub_category_id" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }} id="sub_category_id" class="form-control">
        <option value="">{{ __('general.choose_sub_category') }}</option>
        @foreach($arr_sub_category as $sub_category)
          <option value="{{ $sub_category->id }}" {{ !empty($jobs) && $sub_category->id == $jobs->sub_category->id ? 'selected' : '' }}>{{ $sub_category->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>{{ __('general.name') }}</label>
      <input type="text" name="name" id="name" class="form-control" value="{{ !empty($jobs) ? $jobs->name : '' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
    </div>

    <div class="form-group">
      <label>{{ __('general.description') }}</label>
      <textarea name="description" class="form-control" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}>{{ !empty($jobs) ? $jobs->description : '' }}</textarea>
    </div>

    <div class="form-group">
      <label>{{ __('general.num_people_required') }}</label>
      <div class="input-group">
        <input type="text" name="num_people_required" id="num_people_required" class="form-control" value="{{ !empty($jobs) ? number_format($jobs->num_people_required, 0, ',', '.') : '0' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">Person</span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.list_staff_approve') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="arr_approve_json" value="{{ json_encode($jobs->arr_approve) }}" />
      @endif
      <div class="row">
        <div class="col-12">
          <div class="input-group">
            <input type="text" name="num_staff_approve" id="num_staff_approve" class="form-control" value="{{ !empty($jobs) ? number_format(count($jobs->approve), 0, ',', '.') : '0' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">Person</span>
            </div>
          </div>
        </div>

        <div class="col-12 mt-3">
          <div id="approve_container">
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.list_staff_approve_check_log') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="arr_approve_check_log_json" value="{{ json_encode($jobs->arr_approve_check_log) }}" />
      @endif
      <div class="row">
        <div class="col-12">
          <div class="input-group">
            <input type="text" name="num_staff_approve_check_log" id="num_staff_approve_check_log" class="form-control" value="{{ !empty($jobs) ? number_format(count($jobs->approve_check_log), 0, ',', '.') : '0' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">Person</span>
            </div>
          </div>
        </div>

        <div class="col-12 mt-3">
          <div id="approve_check_log_container">
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.list_staff_approve_salary') }}</label>
      @if(!empty($jobs) && !$jobs->allow_edit)
        <input type="hidden" name="arr_approve_salary_json" value="{{ json_encode($jobs->arr_approve_salary) }}" />
      @endif
      <div class="row">
        <div class="col-12">
          <div class="input-group">
            <input type="text" name="num_staff_approve_salary" id="num_staff_approve_salary" class="form-control" value="{{ !empty($jobs) ? number_format(count($jobs->approve_salary), 0, ',', '.') : '0' }}" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'readonly' }}/>
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">Person</span>
            </div>
          </div>
        </div>

        <div class="col-12 mt-3">
          <div id="approve_salary_container">
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>{{ __('general.image') }}</label>
      @include('layout.upload_multiple_photo_step', [
        "column" => "file_name",
        "form_name" => "image[]",
        "data" => $jobs,
        "id" => "jobs_image",
        "url_image" => "/image/jobs",
      ])
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    {{-- <a class="btn btn-primary" onclick="save_current_page('{{ __('jobs.detail') }}')" href="{{ url('/jobs/action?id='.$jobs->id) }}">{{ __('general.cancel') }}</a>

    <a class="btn btn-primary ml-3" target="_blank" href="{{ url('/jobs/print-qr?id='.$jobs->id) }}">{{ __('general.next') }}</a> --}}
  </div>
</div>

@push('script')
  <script>
    function check_general(){
      var num_staff_approve = $('#num_staff_approve').val()
      var num_staff_approve_check_log = $('#num_staff_approve_check_log').val()
      var num_staff_approve_salary = $('#num_staff_approve_salary').val()
      var message = ""
      var arr_staff = []
      @foreach($arr_staff as $key => $staff)
        arr_staff.push({
          id: '{{ $staff->id }}',
          name: '{{ $staff->name }}',
        })
      @endforeach

      var counter_approve = 0
      var counter_approve_salary = 0
      var counter_approve_check_log = 0
      for(let x = 0; x < num_staff_approve; x++){
        if($('#people_approve'+x).val() == "")
          break
        counter_approve++
      }
      for(let x = 0; x < num_staff_approve_salary; x++){
        if($('#people_approve_salary'+x).val() == "")
          break
        counter_approve_salary++
      }
      for(let x = 0; x < num_staff_approve_check_log; x++){
        if($('#people_approve_check_log'+x).val() == "")
          break
        counter_approve_check_log++
      }
      
      // if(!$('#radio-publish').is(':checked') && !$('#radio-not_publish').is(':checked'))
      //   message = "{{ __('general.status_publish_not_choosen') }}"
      if(!$('#radio-urgent').is(':checked') && !$('#radio-not_urgent').is(':checked'))
        message = "{{ __('general.status_urgent_not_choosen') }}"
      else if($('#event_id').val() == "")
        message = "{{ __('general.event_not_choosen') }}"
      // else if($('#department_id').val() == "")
      //   message = "{{ __('general.department_not_choosen') }}"
      else if($('#category_id').val() == "")
        message = "{{ __('general.category_not_choosen') }}"
      else if($('#sub_category_id').val() == "")
        message = "{{ __('general.sub_category_not_choosen') }}"
      else if($('#name').val() == "")
        message = "{{ __('general.name_empty') }}"
      else if($('#num_people').val() == "" || $('#num_people').val() == "0")
        message = "{{ __('general.num_people_empty') }}"
      else if($('#num_people_required').val() == "" || $('#num_people_required').val() == "0")
        message = "{{ __('general.num_people_required_empty') }}"
      else if($('#num_staff_approve').val() == "" || $('#num_staff_approve').val() == "0")
        message = "{{ __('general.num_staff_approve_empty') }}"
      else if($('#num_staff_approve_check_log').val() == "" || $('#num_staff_approve_check_log').val() == "0")
        message = "{{ __('general.num_staff_approve_check_log_empty') }}"
      else if($('#num_staff_approve_salary').val() == "" || $('#num_staff_approve_salary').val() == "0")
        message = "{{ __('general.num_staff_approve_salary_empty') }}"
      else if(counter_approve < num_staff_approve)
        message = "{{ __('general.list_staff_approve_not_choosen') }}"
      else if(counter_approve_salary < num_staff_approve_salary)
        message = "{{ __('general.list_staff_approve_salary_not_choosen') }}"
      else if(counter_approve_check_log < num_staff_approve_check_log)
        message = "{{ __('general.list_staff_approve_check_log_not_choosen') }}"
      return message
    }

    async function get_sub_category(category_id){
      var response = await request('{{ url("/api/sub-category") }}?is_publish=1&category_id=' + category_id)
      if(response != null){
        if(response.status === "success"){
          var str = `
            <option value="">{{ __('general.choose_sub_category') }}</option>
          `
          for(let sub_category of response.data)
            str += `<option value="${sub_category.id}">${sub_category.name}</option>`
          // console.log(response.data)
          $('#sub_category_id').html(str)
        }
      }
    }

    async function get_city(province_id){
      var response = await request('{{ url("/api/city/all") }}?is_publish=1&province_id=' + province_id)
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

    function manage_arr_staff_approve(){
      var num_staff = $('#num_staff_approve').val()
      var arr_approve = []
      var arr_staff = []
      @if(!empty($jobs))
        @foreach($jobs->approve as $approve)
          arr_approve.push({
            id: '{{ $approve->id }}',
            user_id: '{{ $approve->user_id }}',
          })
        @endforeach
      @endif
      @foreach($arr_staff as $staff)
        var data = {
          id: '{{ $staff->id }}',
          name: '{{ $staff->name }}',
        }
        
        @if(!empty($staff->sub_category))
          data.sub_category = {
            id: '{{ $staff->sub_category->id }}',
            name: '{{ $staff->sub_category->name }}',
          }
        @endif

        arr_staff.push(data)
      @endforeach
      
      var str = '<div class="row">'
      for(let x = 0; x < num_staff; x++){
        var user_id = ""
        if(arr_approve.length > 0 && arr_approve[x] != null)
          user_id = arr_approve[x].user_id

        str += `
          <div class="col-3 mb-3">
            <div class="d-flex align-items-center">
              <label class="m-0">${x+1}.</label>
              <select name="arr_people_approve[]" class="form-control ml-1 staff_approve" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="people_approve${x}">
                <option value="">{{ __('general.choose_staff') }}</option>
                `
        for(let staff of arr_staff){
          flag = false
          for(let y in arr_approve){
            if(y < x && arr_approve[y].user_id == staff.id){
              flag = true
              break
            }
          }
          if(flag)
            continue

          str += `<option value="${staff.id}" ${user_id !== '' && user_id === staff.id ? 'selected' : ''}>${staff.name} ${staff.sub_category != null ? "("+staff.sub_category.name+")" : ""}</option>`
        }
        str += `
              </select>
            </div>
          </div>
        `
      }
      str += '</div>'
      $('#approve_container').html(str)
    }

    function manage_arr_staff_approve_salary(){
      var num_staff = $('#num_staff_approve_salary').val()
      var arr_approve = []
      var arr_staff = []
      @if(!empty($jobs))
        @foreach($jobs->approve_salary as $approve_salary)
          arr_approve.push({
            id: '{{ $approve_salary->id }}',
            user_id: '{{ $approve_salary->user_id }}',
          })
        @endforeach
      @endif
      @foreach($arr_staff as $staff)
        var data = {
          id: '{{ $staff->id }}',
          name: '{{ $staff->name }}',
        }
        
        @if(!empty($staff->sub_category))
          data.sub_category = {
            id: '{{ $staff->sub_category->id }}',
            name: '{{ $staff->sub_category->name }}',
          }
        @endif

        arr_staff.push(data)
      @endforeach
      
      var str = '<div class="row">'
      for(let x = 0; x < num_staff; x++){
        var user_id = ""
        if(arr_approve.length > 0 && arr_approve[x] != null)
          user_id = arr_approve[x].user_id

        str += `
          <div class="col-3 mb-3">
            <div class="d-flex align-items-center">
              <label class="m-0">${x+1}.</label>
              <select name="arr_people_approve_salary[]" class="form-control ml-1 staff_approve_salary" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="people_approve_salary${x}">
                <option value="">{{ __('general.choose_staff') }}</option>
                `
        for(let staff of arr_staff){
          flag = false
          for(let y in arr_approve){
            if(y < x && arr_approve[y].user_id == staff.id){
              flag = true
              break
            }
          }
          if(flag)
            continue

          str += `<option value="${staff.id}" ${user_id !== '' && user_id === staff.id ? 'selected' : ''}>${staff.name} ${staff.sub_category != null ? "("+staff.sub_category.name+")" : ""}</option>`
        }
        str += `
              </select>
            </div>
          </div>
        `
      }
      str += '</div>'
      $('#approve_salary_container').html(str)
    }

    function manage_arr_staff_approve_check_log(){
      var num_staff = $('#num_staff_approve_check_log').val()
      var arr_approve = []
      var arr_staff = []
      @if(!empty($jobs))
        @foreach($jobs->approve_check_log as $approve_check_log)
          arr_approve.push({
            id: '{{ $approve_check_log->id }}',
            user_id: '{{ $approve_check_log->user_id }}',
          })
        @endforeach
      @endif
      @foreach($arr_staff as $staff)
      var data = {
          id: '{{ $staff->id }}',
          name: '{{ $staff->name }}',
        }
        
        @if(!empty($staff->sub_category))
          data.sub_category = {
            id: '{{ $staff->sub_category->id }}',
            name: '{{ $staff->sub_category->name }}',
          }
        @endif

        arr_staff.push(data)
      @endforeach
      
      var str = '<div class="row">'
      for(let x = 0; x < num_staff; x++){
        var user_id = ""
        if(arr_approve.length > 0 && arr_approve[x] != null)
          user_id = arr_approve[x].user_id

        str += `
          <div class="col-3 mb-3">
            <div class="d-flex align-items-center">
              <label class="m-0">${x+1}.</label>
              <select name="arr_people_approve_check_log[]" class="form-control ml-1 staff_approve_check_log" {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? '' : 'disabled' }} id="people_approve_check_log${x}">
                <option value="">{{ __('general.choose_staff') }}</option>
                `
        for(let staff of arr_staff){
          flag = false
          for(let y in arr_approve){
            if(y < x && arr_approve[y].user_id == staff.id){
              flag = true
              break
            }
          }
          if(flag)
            continue

          str += `<option value="${staff.id}" ${user_id !== '' && user_id === staff.id ? 'selected' : ''}>${staff.name} ${staff.sub_category != null ? "("+staff.sub_category.name+")" : ""}</option>`
        }
        str += `
              </select>
            </div>
          </div>
        `
      }
      str += '</div>'
      $('#approve_check_log_container').html(str)
    }
    
    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
var setTimeout = null

@if(!empty($jobs))
  @foreach($jobs->image as $image)
    arr_url_image.push('{{ url("/image/jobs?file_name=".$image->file_name) }}')
    arr_file_name.push('{{ $image->file_name }}')
    arr_id.push('{{ $image->id }}')
    arr_image.push(null)
  @endforeach
  on_change_multiple_image()

  $('#num_staff_approve').val({{ count($jobs->approve) }})
  manage_arr_staff_approve()
  $('#num_staff_approve_salary').val({{ count($jobs->approve_salary) }})
  manage_arr_staff_approve_salary()
  $('#num_staff_approve_check_log').val({{ count($jobs->approve_check_log) }})
  manage_arr_staff_approve_check_log()
@endif

$('#category_id').change(() => {
  this.get_sub_category($('#category_id').val())
})
$('#province_id').change(() => {
  this.get_city($('#province_id').val())
})
$('#submit').click((e) => {
  
})
$('#num_people_required').keyup(() => {
  $('#num_people_required').val(to_currency_format($('#num_people_required').val()))
})
$('.staff_approve').change(() => {
  manage_arr_staff_approve()
})
$('#num_staff_approve').keyup(() => {
  $('#num_staff_approve').val(to_currency_format($('#num_staff_approve').val()))
  
  if(setTimeout != null)
    clearTimeout(setTimeout)

  setTimeout = window.setTimeout(() => {
    var num_staff_approve = to_currency_format($('#num_staff_approve').val())
    @if(!empty($jobs))
      var arr_approve = []
      @foreach($jobs->approve as $approve)
        arr_approve.push({
          id: '{{ $approve->id }}',
          user_id: '{{ $approve->user_id }}',
          status_approve: '{{ $approve->status_approve }}',
        })
      @endforeach

      if(arr_approve[num_staff_approve - 1] != null && arr_approve[num_staff_approve - 1].status == "approved")
        num_staff_approve = arr_approve.length
    @endif
    
    $('#num_staff_approve').val(num_staff_approve)
    manage_arr_staff_approve()
  }, 100)
})
$('.staff_approve_salary').change(() => {
  manage_arr_staff_approve_salary()
})
$('#num_staff_approve_salary').keyup(() => {
  $('#num_staff_approve_salary').val(to_currency_format($('#num_staff_approve_salary').val()))
  
  if(setTimeout != null)
    clearTimeout(setTimeout)

  setTimeout = window.setTimeout(() => {
    var num_staff_approve_salary = to_currency_format($('#num_staff_approve_salary').val())
    @if(!empty($jobs))
      var arr_approve_salary = []
      @foreach($jobs->approve_salary as $approve_salary)
        arr_approve_salary.push({
          id: '{{ $approve_salary->id }}',
          user_id: '{{ $approve_salary->user_id }}',
          status_approve: '{{ $approve_salary->status_approve }}',
        })
      @endforeach

      if(arr_approve_salary[num_staff_approve_salary - 1] != null && arr_approve_salary[num_staff_approve_salary - 1].status == "approved")
        num_staff_approve_salary = arr_approve_salary.length
    @endif
    
    $('#num_staff_approve_salary').val(num_staff_approve_salary)
    manage_arr_staff_approve_salary()
  }, 100)
})
$('.staff_approve_check_log').change(() => {
  manage_arr_staff_approve_check_log()
})
$('#num_staff_approve_check_log').keyup(() => {
  $('#num_staff_approve_check_log').val(to_currency_format($('#num_staff_approve_check_log').val()))
  
  if(setTimeout != null)
    clearTimeout(setTimeout)

  setTimeout = window.setTimeout(() => {
    var num_staff_approve_check_log = to_currency_format($('#num_staff_approve_check_log').val())
    @if(!empty($jobs))
      var arr_approve_check_log = []
      @foreach($jobs->approve_check_log as $approve_check_log)
        arr_approve_check_log.push({
          id: '{{ $approve_check_log->id }}',
          user_id: '{{ $approve_check_log->user_id }}',
          status_approve: '{{ $approve_check_log->status_approve }}',
        })
      @endforeach

      if(arr_approve_check_log[num_staff_approve_check_log - 1] != null && arr_approve_check_log[num_staff_approve_check_log - 1].status == "approved")
        num_staff_approve_check_log = arr_approve_check_log.length
    @endif
    
    $('#num_staff_approve_check_log').val(num_staff_approve_check_log)
    manage_arr_staff_approve_check_log()
  }, 100)
})
$('#person_in_charge_phone').keyup(() => {
  $('#person_in_charge_phone').val(phone_validation($('#person_in_charge_phone').val()))
})
$('#interviewer_phone').keyup(() => {
  $('#interviewer_phone').val(phone_validation($('#interviewer_phone').val()))
})
$('#radio-company').click(() => {
  $('#company_layout').removeClass('d-none')
  $('#event_layout').addClass('d-none')
})
$('#radio-event').click(() => {
  $('#company_layout').addClass('d-none')
  $('#event_layout').removeClass('d-none')
})
@endpush