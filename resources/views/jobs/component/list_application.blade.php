<div class="card">
  <div class="card-body">
    <h5 class="mb-0 text-gray-800 font-weight-bold">List Applicant & Accepted</h5>
    <div class="mt-3 table-responsive">
      <table class="table w-100" id="list_application_datatable">
        <thead>
          <tr>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.gender') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.type') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.birth_date') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.apply_date') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var application_datatable = null

  function showUploadPKWT(row){
    $('#jobs_pkwt_jobs_application_id').val(row.id)
    $('#jobs_upload_pkwt_modal').modal('show')
  }

  function showUploadPKHL(row){
    $('#jobs_pkhl_jobs_application_id').val(row.id)
    $('#jobs_upload_pkhl_modal').modal('show')
  }

  $(document).ready(function () {
    application_datatable = $('#list_application_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/application?jobs_id='.$jobs->id) }}",
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
        {"data" : "gender_format", name: "user.gender"},
        {"data" : "type_name", name: "type.name"},
        {"data" : "birth_date_format", name: "user.birth_date"},
        {"data" : "created_at_format", name: "jobs_application.created_at"},
        {"data" : "status_format", name: "status"},
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
            str += '<div style="width: auto">'
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('jobs.detail') }}')" href="{{ url('/user/customer') }}/${row.user.type.name == "customer_oncall" ? 'oncall' : 'regular'}/detail?id=${row.user.id}&jobs_application_id=${row.id}{{ Request::has('from') ? '&from='.Request::get('from') : '' }}")>{{ __('general.detail') }}</a>`
              if(row.jobs.criteria.length > 0 && row.jobs.criteria[0].has_pkwt == 1)
                str += `<button class="btn btn-primary ml-3" onclick='showUploadPKWT(${json})'>Upload PKWT</button>`
              if(row.jobs.criteria.length > 0 && row.jobs.criteria[0].has_pkhl == 1)
                str += `<button class="btn btn-primary ml-3" onclick='showUploadPKHL(${json})'>Upload PKHL</button>`
              if(row.pkwt_file_name != null)
                str += `<a class="btn btn-primary ml-3" target="_blank" href="{{ url('/jobs/application/pkwt/download') }}?id=${row.id}">Download PKWT</a>`
              if(row.pkhl_file_name != null)
                str += `<a class="btn btn-primary ml-3" target="_blank" href="{{ url('/jobs/application/pkhl/download') }}?id=${row.id}">Download PKHL</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush