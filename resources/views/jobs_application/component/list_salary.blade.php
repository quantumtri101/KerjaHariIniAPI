<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_salary_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_approve') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.sent_date') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var salary_datatable = null

  $(document).ready(function () {
    salary_datatable = $('#list_salary_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/application/salary?jobs_application_id='.$jobs_application->id) }}",
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
        {"data" : "name", name: "type"},
        {"data" : "salary_format", name: "salary"},
        {"data" : "status_approve", name: "is_approve"},
        {"data" : "sent_at_format", name: "sent_at"},
      ],
      "columnDefs" : [
        // {
        //   targets: -1,
        //   data: null,
        //   sorting: false,
        //   render: function(data, type, row, meta) {
        //     var json = JSON.stringify(row)
            
        //     var str = ""
        //     str += '<div style="width: 150px">'
        //       str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('jobs_application.detail') }}')" href="{{ url('/jobs/salary/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
        //       str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/jobs/salary/delete') }}?id=${row.id}')")>Decline</a>`
        //     str += '</div>'
        //     return str
        //   },
        // },
      ]
    })
  })
</script>
@endpush