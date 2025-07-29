<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_check_log_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.type') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.date') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var check_log_datatable = null

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
        url : "{{ url('api/check-log?jobs_application_id='.$jobs_application->id) }}",
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
        {"data" : "type_format", name: "type"},
        {"data" : "date_format", name: "date"},
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
        //       str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('jobs_application.detail') }}')" href="{{ url('/jobs/check_log/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
        //       str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/jobs/check_log/delete') }}?id=${row.id}')")>Decline</a>`
        //     str += '</div>'
        //     return str
        //   },
        // },
      ]
    })
  })
</script>
@endpush