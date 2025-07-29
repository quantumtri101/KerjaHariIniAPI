<div>
  <div class="mt-3">
    <table class="table w-100" id="list_jobs_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.category_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.salary_regular') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.salary_casual') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var jobs_datatable = null

  $(document).ready(function () {
    jobs_datatable = $('#list_jobs_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs?user_id='.$ro->id) }}",
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
        {"data" : "category_name", name: "category.name"},
        {"data" : "salary_regular_format", name: "salary_regular"},
        {"data" : "salary_casual_format", name: "salary_casual"},
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
            str += '<div style="width: 150px">'
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('ro.detail') }}')" href="{{ url('/jobs/detail') }}?id=${row.id}&from=ro")>{{ __('general.detail') }}</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush