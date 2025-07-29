<div>
  @if((Auth::user()->type->name == "RO" || Auth::user()->type->name == "admin") && !$salary_already_requested_all)
    <button class="btn btn-primary" data-toggle="modal" data-target="#salary_request_approve_all">Request All to Client</button>
  @elseif(Auth::user()->type->name != "RO" && Auth::user()->type->name != "admin")
    @if($allow_approve_salary)
    <div id="approve_salary_column">
      <form method="POST" class="d-inline-block" action="{{ url('/jobs/shift/approve/salary') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $jobs_shift->id }}"/>
        <input type="hidden" name="approve_salary_type" value="per_staff"/>
        <button class="btn btn-primary" {{ $jobs_shift->approve_salary_type != "none" ? 'disabled' : '' }}>{{ __('general.approve_per_staff') }}</button>
      </form>
      <form method="POST" class="d-inline-block" action="{{ url('/jobs/shift/approve/salary') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $jobs_shift->id }}"/>
        <input type="hidden" name="approve_salary_type" value="all"/>
        <button class="btn btn-primary" {{ $jobs_shift->approve_salary_type != "none" ? 'disabled' : '' }}>{{ __('general.approve_all') }}</button>
      </form>
    </div>
    @endif
  @endif
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_salary_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.gender') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.id_no') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.type') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.decline_reason') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_approve') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.'.($allow_approve_salary ? 'approval' : 'action')) }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('layout.modal.edit_salary_verification')
@include('layout.modal.decline_salary_verification')
@include('layout.modal.salary_request_approve')
@include('layout.modal.salary_request_approve_all')

@push('script')
<script type="text/javascript">
  var jobs_datatable = null

  function approvedSalaryAction(data){
    $('#salary_user_id').val(data.user.id)
    $('#salary_jobs_shift_id').val('{{ $jobs_shift->id }}')
    $('#salary_salary_verification').val(data.salary_init.toLocaleString(locale_string))
    $('#edit_salary_verification').modal('show')
  }

  function declinedSalaryAction(data){
    $('#salary_decline_user_id').val(data.user.id)
    $('#salary_decline_jobs_shift_id').val('{{ $jobs_shift->id }}')
    $('#salary_decline_modal').modal('show')
  }

  function salaryRequestAction(data){
    $('#salary_request_approve_user_id').val(data.user.id)
    $('#salary_request_approve').modal('show')
  }

  function downloadSalaryDocument(data){
    for(let x in data.salary_document){
      setTimeout(() => {
        window.open("{{ url('/salary/document/download') }}?id=" + data.salary_document[x].id, "_blank")
      }, 100 * x);
    }
  }

  $(document).ready(function () {
    jobs_datatable = $('#list_salary_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      dom: 'Bfrtip',
      buttons: [
        // {
        //   className: 'btn btn-primary',   
        //   text : "{{ __('general.add') }}",
        //   action: function ( e, dt, node, config ) {
        //     save_current_page("{{ __('check_log.title') }}")
        //     location.href = "{{ url('/check-log/action') }}"
        //   },
        //   init: function(api, node, config) {
        //     $(node).removeClass('dt-button')
        //   },
        // },
      ],
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : `{!! url('api/jobs/application').'?arr_status=["working","done"]&jobs_id='.$jobs_shift->jobs->id !!}`,
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "drawCallback": function (settings) { 
        // Here the response
        var response = settings.json;
        if(response.recordsTotal == 0)
          $('#approve_salary_column').addClass('d-none')
        else
          $('#approve_salary_column').removeClass('d-none')
      },
      "order" : [[0, "asc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "user_name", name: "user.name"},
        {"data" : "gender_format", name: "user.gender"},
        {"data" : "id_no", name: "user.id_no"},
        {"data" : "salary_approve_format", name: "salary_approve_format"},
        {"data" : "type_name_format", name: "type.name"},
        {"data" : "decline_reason_salary", name: "resume.decline_reason_salary"},
        {"data" : "status_salary_approve_format", name: "status_salary_approve_format"},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var json = JSON.stringify(row)

            var status = ""
            if(row.is_approve_salary == 'approved')
              status = "Approved"
            else if(row.is_approve_salary == 'declined')
              status = "Declined"
            else if(row.is_approve_salary == 'not_yet_approved')
              status = "Not yet Approved"
            else if(row.is_approve_salary == 'requested')
              status = "Requested"
            
            var str = ""
            str += '<div style="width: auto">'
              @if($allow_approve_salary)
                @if($jobs_shift->approve_salary_type != "none")
                  if(row.is_approve_salary == 'requested')
                    str += `
                      <form method="POST" action="{{ url('/salary/approve/salary') }}" class="d-inline-block">
                        @csrf
                        <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>
                        <input type="hidden" name="user_id" value="${row.user_id}"/>
                        <input type="hidden" name="is_approve_salary" value="approved"/>
                        <button class="btn btn-primary" >Accept</button>
                      </form>
                      <button class="btn btn-primary" onclick='declinedSalaryAction(${json})'>Decline</button>
                    `
                @endif
              @elseif(Auth::user()->type->name == "RO")
                if(row.is_approve_salary == 'not_yet_approved' || row.is_approve_salary == 'declined')
                  str += `
                    <button class="btn btn-primary" onclick='approvedSalaryAction(${json})'>Edit Salary</button>
                    <button class="btn btn-primary" onclick='salaryRequestAction(${json})'>Request to Client</button>
                  `
              @endif

              if(row.salary_document.length > 0)
                str += `<button class="btn btn-primary mr-1" onclick='downloadSalaryDocument(${json})'>{{ __('general.download_all_file') }}</button>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush