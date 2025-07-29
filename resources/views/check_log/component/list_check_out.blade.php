<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_check_out_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.type') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.gender') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.id_no') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.date') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var jobs_datatable = null

  $(document).ready(function () {
    jobs_datatable = $('#list_check_out_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/check-log').'?type=check_out&jobs_shift_id='.$jobs_shift->id }}",
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
        {"data" : "type_name", name: "type.name"},
        {"data" : "gender", name: "resume.gender"},
        {"data" : "id_no", name: "resume.id_no"},
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
        //       str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('customer.detail') }}')" href="{{ url('/jobs/detail') }}?id=${row.id}&from=customer")>{{ __('general.detail') }}</a>`
        //     str += '</div>'
        //     return str
        //   },
        // },
      ]
    })
  })
</script>
@endpush