<div>
  @if($allow_approve_salary)
  <div id="approve_additional_salary_column">
    <form method="POST" class="d-inline-block" action="{{ url('/jobs/shift/approve/additional-salary') }}">
      @csrf
      <input type="hidden" name="id" value="{{ $jobs_shift->id }}"/>
      <input type="hidden" name="approve_additional_salary_type" value="per_staff"/>
      <button class="btn btn-primary" {{ $jobs_shift->approve_additional_salary_type != "none" ? 'disabled' : '' }}>{{ __('general.approve_per_staff') }}</button>
    </form>
    <form method="POST" class="d-inline-block" action="{{ url('/jobs/shift/approve/additional-salary') }}">
      @csrf
      <input type="hidden" name="id" value="{{ $jobs_shift->id }}"/>
      <input type="hidden" name="approve_additional_salary_type" value="all"/>
      <button class="btn btn-primary" {{ $jobs_shift->approve_additional_salary_type != "none" ? 'disabled' : '' }}>{{ __('general.approve_all') }}</button>
    </form>
  </div>
  @elseif(Auth::user()->type->name == "RO")
  <div>
    <button class="btn btn-primary" onclick='approvedSalaryAdditionalAction()'>Add Overtime Salary</button>
  </div>
  @endif
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_additional_salary_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.gender') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.id_no') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.overtime_salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.type') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.decline_reason') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_approve') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.'.($allow_approve_salary ? 'approval' : 'action')) }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('layout.modal.edit_salary_additional_verification')
@include('layout.modal.decline_salary_additional_verification')

@push('script')
<script type="text/javascript">
  var jobs_datatable = null

  function approvedSalaryAdditionalAction(){
    $('#salary_additional_jobs_shift_id').val('{{ $jobs_shift->id }}')
    $('#edit_salary_additional_verification').modal('show')
  }

  function declinedSalaryAdditionalAction(data){
    $('#salary_additional_decline_user_id').val(data.user.id)
    $('#salary_additional_decline_jobs_shift_id').val('{{ $jobs_shift->id }}')
    $('#salary_additional_decline_modal').modal('show')
  }

  function downloadAdditionalSalaryDocument(data){
    for(let x in data.additional_salary_document){
      setTimeout(() => {
        window.open("{{ url('/additional-salary/document/download') }}?id=" + data.additional_salary_document[x].id, "_blank")
      }, 100 * x);
    }
  }

  $(document).ready(function () {
    jobs_datatable = $('#list_additional_salary_datatable').DataTable({
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
        url : `{{ url('api/jobs/application') }}?arr_is_approve_additional_salary=["requested","approved","declined"]&jobs_id={{ $jobs_shift->jobs->id }}`,
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
          $('#approve_additional_salary_column').addClass('d-none')
        else
          $('#approve_additional_salary_column').removeClass('d-none')
      },
      "order" : [[0, "asc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "user_name", name: "user.name"},
        {"data" : "gender_format", name: "resume.gender"},
        {"data" : "id_no", name: "resume.id_no"},
        {"data" : "additional_salary_format", name: "additional_salary_format"},
        {"data" : "type_name_format", name: "type.name"},
        {"data" : "decline_reason_additional_salary", name: "resume.decline_reason_additional_salary"},
        {"data" : "status_additional_salary_approve_format", name: "status_additional_salary_approve_format"},
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
            if(row.is_approve_additional_salary == 'approved')
              status = "Approved"
            else if(row.is_approve_additional_salary == 'declined')
              status = "Declined"
            else if(row.is_approve_additional_salary == 'not_yet_approved')
              status = "Not yet Approved"
            else if(row.is_approve_additional_salary == 'requested')
              status = "Requested"
            
            var str = ""
            str += '<div style="width: auto">'
              @if($allow_approve_salary)
                @if($jobs_shift->approve_additional_salary_type != "none")
                  if(row.is_approve_additional_salary == 'requested')
                    str += `
                      <form method="POST" action="{{ url('/salary/approve/additional-salary') }}" class="d-inline-block">
                        @csrf
                        <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>
                        <input type="hidden" name="user_id" value="${row.user_id}"/>
                        <input type="hidden" name="is_approve_additional_salary" value="approved"/>
                        <button class="btn btn-primary" >Accept</button>
                      </form>
                      <button class="btn btn-danger" onclick='declinedSalaryAdditionalAction(${json})'>Decline</button>
                    `
                @endif
              @endif

              if(row.additional_salary_document.length > 0)
                str += `<button class="btn btn-primary mr-1" onclick='downloadAdditionalSalaryDocument(${json})'>{{ __('general.download_all_file') }}</button>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush