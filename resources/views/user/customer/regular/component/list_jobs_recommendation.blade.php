<div>
  <div class="mt-3">
    <table class="table w-100" id="list_jobs_recommendation_datatable">
      <thead>
        <tr>
          {{-- <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.category_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.city_name') }}</th> --}}
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.range_salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var jobs_recommendation_datatable = null

  $(document).ready(function () {
    jobs_recommendation_datatable = $('#list_jobs_recommendation_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/recommendation?user_id='.$customer_regular->id) }}",
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
        {"data" : "range_salary_format", name: "range_salary.min_salary"},
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
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('customer_regular.detail') }}')" href="{{ url('/jobs-recommendation/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush