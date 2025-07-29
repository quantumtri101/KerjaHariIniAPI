<div>
  <input type="hidden" name="arr_user_casual" id="arr_user_casual"/>
  
  <div>
    <label id="total_user_casual_applied">{{ __('general.total_user_casual_applied') }}: {{ __('general.num_person', ['num' => 0]) }}</label>
  </div>

  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_user_casual_datatable">
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
  var arr_user_casual = []

  function set_casual_applied(data){
    arr_user_casual.push({
      id: data,
    })
    arr_application.push({
      user_id: data,
    })
    manage_arr_user_casual(data, 'add')
  }

  function set_casual_unapplied(data){
    for(let x in arr_user_casual){
      if(arr_user_casual[x].id === data){
        arr_user_casual.splice(x, 1)
        break
      }
    }

    for(let x in arr_application){
      if(arr_application[x].user_id == data){
        arr_application.splice(x, 1)
        break
      }
    }
    manage_arr_user_casual(data, 'sub')
  }

  function manage_arr_user_casual(data, type = "add"){
    $('#arr_user_casual').val(JSON.stringify(arr_user_casual))

    if(type === 'add'){
      $(`.btn-casual-set-applied[id=casual-${data}]`).addClass('d-none')
      $(`.btn-casual-set-unapplied[id=casual-${data}]`).removeClass('d-none')
      
    }
    else if(type === 'sub'){
      $(`.btn-casual-set-applied[id=casual-${data}]`).removeClass('d-none')
      $(`.btn-casual-set-unapplied[id=casual-${data}]`).addClass('d-none')
      
    }
    $('#total_user_casual_applied').html(`
      {{ __('general.total_user_casual_applied') }}: ${arr_user_casual.length} {{ __('general.person') }}
    `)
    this.remaining_user()
  }

  function check_user_casual(){
    var message = ""
    // if(arr_casual.length == 0)
    //   message = "{{ __('general.c') }}"
    return message
  }

  function view_resume_casual(user_id, jobs_application_id, already_applied){
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
user_datatable = $('#list_user_casual_datatable').DataTable({
  "processing" : true,
  "serverSide" : true,
  bLengthChange: false,
  responsive: true,
  language: {
    searchPlaceholder: 'Search...',
    sSearch: '',
    "emptyTable": '{{ __("general.no_staff_met_requirement") }}',
  },
  "ajax" : {
    url : `{{ url('api/user') }}?type=customer_oncall&is_active=1&jobs_id={{ $jobs->id }}&arr_api_type=["recommendation","not_applicant"]`,
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
        var arr_casual = $('#arr_user_casual').val() != "" ? JSON.parse($('#arr_user_casual').val()) : []

        var isSelected = false
        for(let casual of arr_casual){
          if(casual.id === row.id){
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
            str += `<button class="btn btn-primary btn-casual-set-applied ${arr_application.length > 0 ? (flag ? 'd-none' : '') : ''}" ${!isSelected && num_people_required == total_user_applied ? 'disabled' : ''} type="button" id="casual-${row.id}" onclick="set_casual_applied('${row.id}')">{{ __('general.set_applied') }}</button>`
            str += `<button class="btn btn-primary btn-casual-set-unapplied ${arr_application.length > 0 ? (flag ? '' : 'd-none') : 'd-none'}" type="button" id="casual-${row.id}" onclick="set_casual_unapplied('${row.id}')">{{ __('general.set_unapplied') }}</button>`
          {{-- } --}}
          str += `</div>`

          str += `<button class="btn btn-primary ml-3" type="button" onclick="view_resume_casual('${row.id}')" ${row.resume.length == 0 ? 'disabled' : ''}>{{ __('general.view_resume') }}</button>`
        str += '</div>'
        return str
      },
    },
  ]
})
@endpush