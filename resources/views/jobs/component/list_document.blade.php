<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_document_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.file_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var user_datatable = null

  $(document).ready(function () {
    user_datatable = $('#list_document_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/document') }}?jobs_id={{ $jobs->id }}",
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
        {"data" : "file_name", name: "file_name"},
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
              str += `
                <a href="{{ url('/jobs/document/download') }}?id=${row.id}" class="btn btn-primary">{{ __('general.download') }}</button>
                @if(Auth::user()->type->name == "staff" && $jobs->status_approve == "not_yet_approved")
                  <a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/jobs/document/delete') }}?id=${row.id}')">Delete</a>
                @endif
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