<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_check_log_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.user_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.check_in_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.check_out_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@if(Auth::user()->type->name == 'staff' && Auth::user()->company_position->name == "HRD")
  @include('layout.modal.check_log_approve_date')
@elseif(Auth::user()->type->name == 'staff' && Auth::user()->company_position->name == "Finance")
  @include('layout.modal.check_log_approve_salary')
@endif

@push('script')
<script type="text/javascript">
  var check_log_datatable = null

  function showCheckLogApproveModal(jobs_application_id, check_in_at, check_out_at){
    $('#check_log_approve_date_jobs_application_id').val(jobs_application_id)
    $('#checkintimepicker').val(check_in_at)
    $('#checkouttimepicker').val(check_out_at)
    $('#check_log_approve_date_modal').modal('show')

    init_check_log_start_date()
    init_check_log_end_date()
  }

  function showSalaryApproveModal(jobs_application_id, salary_approve){
    $('#check_log_approve_salary_jobs_application_id').val(jobs_application_id)
    $('#check_log_approve_salary_salary').val(salary_approve.toLocaleString(locale_string))
    $('#check_log_approve_salary_modal').modal('show')
  }

  $(document).ready(function () {
    check_log_datatable = $('#list_check_log_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/check-log') }}?jobs_id={{ $jobs->id }}&api_type=check_in",
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
        {"data" : "user_name", name: "user.name"},
        {"data" : "check_in_at_format", name: "date"},
        {"data" : "check_out_at_format", name: "date"},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var json = JSON.stringify(row)
            
            var str = ""
            str += '<div style="">'
              @if(Auth::user()->type->name == 'staff' && Auth::user()->company_position->name == "HRD")
                if(row.is_approve_check_log == 0)
                  str += `<a class="btn btn-primary" href="#!" onclick="showCheckLogApproveModal('${row.jobs_application_id}', '${row.check_in_at}', '${row.check_out_at}')">Approve</a>`
              @elseif(Auth::user()->type->name == 'staff' && Auth::user()->company_position->name == "Finance")
                if(row.is_approve_salary == 0)
                  str += `<a class="btn btn-primary" href="#!" onclick="showSalaryApproveModal('${row.jobs_application_id}', ${row.salary_approve})">Approve</a>`
              @endif
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush