<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_jobs_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.category_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.city_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.salary') }}</th>
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
        url : "{{ url('api/jobs?event_id='.$event->id) }}",
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
        {"data" : "city_name", name: "city.name"},
        {"data" : "salary_format", name: "salary"},
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
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('customer.detail') }}')" href="{{ url('/jobs/detail') }}?id=${row.id}&from=customer")>{{ __('general.detail') }}</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush