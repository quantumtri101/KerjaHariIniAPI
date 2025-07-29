<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_approve_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.user_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_approve') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.updated_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.document') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.decline_reason') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var approve_datatable = null

  function showDeclineReasonModal(jobs_id, decline_reason){
    $('#jobs_decline_jobs_id').val(jobs_id)
    $('#decline_reason').val(decline_reason)
    $('#decline_reason').attr('disabled', true)
    $('#decline_button').addClass('d-none')
    $('#jobs_decline_modal').modal('show')
  }

  function downloadDocument(data){
    for(let x in data.document){
      setTimeout(() => {
        window.open("{{ url('/jobs/document/download') }}?id=" + data.document[x].id, "_blank")
      }, 100 * x);
    }
  }

  $(document).ready(function () {
    approve_datatable = $('#list_approve_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/approve?jobs_id='.$jobs->id) }}",
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : false,
      // deferLoading: 2,
      "columns" : [
        {"data" : "user_name", name: "user.name", "orderable" : false},
        {"data" : "status_approve", name: "is_approve", "orderable" : false},
        {"data" : "updated_at_format", name: "updated_at", "orderable" : false},
        {"data" : "document_str_format", name: "approve_at", "orderable" : false},
        {"data" : "decline_reason", name: "decline_reason", "orderable" : false},
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
              if(row.document_str_format != '')
                str += `<button class="btn btn-primary mr-3" onclick='downloadDocument(${json})'>{{ __('general.download_all_file') }}</button>`
              if(row.status_approve == '{{ __("general.declined") }}')
                str += `<button class="btn btn-primary" onclick="showDeclineReasonModal('${row.id}', '${row.decline_reason}')">{{ __('general.view_decline_reason') }}</button>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush