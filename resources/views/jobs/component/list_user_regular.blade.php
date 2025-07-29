<div>
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

  function add_applied(user_id){
    $('#user_id').val(user_id)
    $('#applied_from').val('system')
    set_applied_data_modal()
  }

  function view_resume(user_id, jobs_application_id, already_applied){
    if(already_applied)
      window.localStorage.setItem('menu', 'resume_data')
    save_current_page('{{ __('customer.detail') }}')
    if(!already_applied)
      location.href = '{{ url("/resume/detail") }}?user_id=' + user_id
    else
      location.href = '{{ url("/jobs/application/detail") }}?id=' + jobs_application_id
  }

  $(document).ready(function () {
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
        url : "{{ url('api/user') }}?company_id={{ $jobs->company->id }}&type=customer_regular",
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
            var arr_application = []
            var flag = false
            var application = null
            @foreach($jobs->application as $application)
              arr_application.push({
                id: '{{ $application->id }}',
                user_id: '{{ $application->user_id }}',
              })
            @endforeach
            for(let application1 of arr_application){
              if(row.id === application1.user_id){
                flag = true
                application = application1
                break
              }
            }
            
            var str = ""
            str += '<div style="">'
              // if(!flag && {{ $jobs->num_people_required > count($jobs->application) ? 'true' : 'false' }} && {{ $jobs->is_approve == 1 ? 'true' : 'false' }})
              //   str += `
              //     <form method="POST" class="d-inline-block" action="{{ url('/jobs/application') }}">
              //       @csrf
              //       <input type="hidden" name="user_id" value="${row.id}"/>
              //       <input type="hidden" name="jobs_id" value="{{ $jobs->id }}"/>
              //       <input type="hidden" name="is_approve_corp" value="1"/>
              //       <button class="btn btn-primary">
              //         {{ __('general.set_applied') }}
              //       </button>
              //     </form>
              //   `

              str += `
              <button class="btn btn-primary" onclick="view_resume('${row.id}', '${application != null ? application.id : ''}', ${application != null})" ${row.resume.length == 0 ? 'disabled' : ''}>{{ __('general.view_resume') }}</button>
              `
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush