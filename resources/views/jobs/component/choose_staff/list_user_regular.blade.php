<div>
  <input type="hidden" name="arr_user_regular" id="arr_user_regular"/>
  
  <div>
    <label id="total_user_regular_applied">{{ __('general.total_user_regular_applied') }}: {{ __('general.num_person', ['num' => 0]) }}</label>
  </div>

  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_user_regular_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.email') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.phone') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var user_datatable = null
  var arr_user_regular = []

  function set_regular_applied(data){
    arr_user_regular.push({
      id: data,
    })
    arr_application.push({
      user_id: data,
    })
    manage_arr_user_regular(data, 'add')
  }

  function set_regular_unapplied(data){
    for(let x in arr_user_regular){
      if(arr_user_regular[x].id === data){
        arr_user_regular.splice(x, 1)
        break
      }
    }
    for(let x in arr_application){
      if(arr_application[x].user_id == data){
        arr_application.splice(x, 1)
        break
      }
    }
    manage_arr_user_regular(data, 'sub')
  }

  function manage_arr_user_regular(data, type = "add"){
    $('#arr_user_regular').val(JSON.stringify(arr_user_regular))

    if(type === 'add'){
      $(`.btn-regular-set-applied[id=regular-${data}]`).addClass('d-none')
      $(`.btn-regular-set-unapplied[id=regular-${data}]`).removeClass('d-none')
      
    }
    else if(type === 'sub'){
      $(`.btn-regular-set-applied[id=regular-${data}]`).removeClass('d-none')
      $(`.btn-regular-set-unapplied[id=regular-${data}]`).addClass('d-none')
      
    }
    $('#total_user_regular_applied').html(`
      {{ __('general.total_user_regular_applied') }}: ${arr_user_regular.length} {{ __('general.person') }}
    `)
    this.remaining_user()
  }

  function check_user_regular(){
    var message = ""
    // if(arr_regular.length == 0)
    //   message = "{{ __('general.c') }}"
    return message
  }

  function view_resume_regular(user_id, jobs_application_id, already_applied){
    if(already_applied)
      window.localStorage.setItem('menu', 'resume_data')
    save_current_page('{{ __("customer.detail") }}')
    location.href = '{{ url("/resume/detail") }}?user_id=' + user_id
  }

  $(document).ready(function () {
  })
</script>
@endpush

@push('afterScript')
user_datatable = $('#list_user_regular_datatable').DataTable({
  "processing" : true,
  "serverSide" : true,
  bLengthChange: false,
  responsive: true,
  language: {
    searchPlaceholder: 'Search...',
    sSearch: '',
  },
  "ajax" : {
    url : `{{ url('api/user') }}?company_id={{ $jobs->company->id }}&is_active=1&type=customer_regular&jobs_id={{ $jobs->id }}&arr_api_type=["not_applicant"]`,
    type : "GET",
    dataType : "json",
    headers : {
      "content-type": "application/json",
      "accept": "application/json",
      "X-CSRF-TOKEN": "{{csrf_token()}}"
    },
  },
  "order" : [[0, "asc"]],
  // deferLoading: 2,
  "columns" : [
    {"data" : "name", name: "name"},
    {"data" : "email", name: "email"},
    {"data" : "phone", name: "phone"},
    {"data" : "action", "orderable" : false},
  ],
  "columnDefs" : [
    {
      targets: -1,
      data: null,
      sorting: false,
      render: function(data, type, row, meta) {
        var json = JSON.stringify(row)
        var arr_regular = $('#arr_user_regular').val() != "" ? JSON.parse($('#arr_user_regular').val()) : []

        var isSelected = false
        for(let regular of arr_regular){
          if(regular.id === row.id){
            isSelected = true
            break
          }
        }

        var flag = false
        for(let application of arr_application){
          if(row.id === application.user_id){
            flag = true
            break
          }
        }
        var total_user_applied = arr_user_regular.length + arr_user_casual.length
        var num_people_required = {{ $jobs->num_people_required }}
        
        var str = ""
        str += '<div style="">'
          str += `<div class="mr-3 d-inline-block">`
          {{-- if({{ $jobs->num_people_required > count($jobs->application) ? 'true' : 'false' }}){ --}}
            str += `<button class="btn btn-primary btn-regular-set-applied ${arr_application.length > 0 ? (flag ? 'd-none' : '') : ''}" ${!isSelected && num_people_required == total_user_applied ? 'disabled' : ''} type="button" id="regular-${row.id}" onclick="set_regular_applied('${row.id}')">{{ __('general.set_applied') }}</button>`
            str += `<button class="btn btn-primary btn-regular-set-unapplied ${arr_application.length > 0 ? (flag ? '' : 'd-none') : 'd-none'}" type="button" id="regular-${row.id}" onclick="set_regular_unapplied('${row.id}')">{{ __('general.set_unapplied') }}</button>`
          {{-- } --}}
          str += `</div>`

          str += `<button class="btn btn-primary" onclick="view_resume_regular('${row.id}')" ${row.resume.length == 0 ? 'disabled' : ''}>{{ __('general.view_resume') }}</button>`
        str += '</div>'
        return str
      },
    },
  ]
})
@endpush