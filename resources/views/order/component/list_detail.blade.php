<div>
  <div class="mt-3">
    <table class="table w-100" id="list_detail_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.amount') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.price') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.duration') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var detail_datatable = null

  $(document).ready(function () {
    detail_datatable = $('#list_detail_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/order/detail?order_id='.$order->id) }}",
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
        {"data" : "service_name", name: "service.name"},
        {"data" : "amount", name: "amount"},
        {"data" : "price_format", name: "price"},
        {"data" : "duration_format", name: "duration"},
      ],
      "columnDefs" : [
        // {
        //   targets: -1,
        //   data: null,
        //   sorting: false,
        //   render: function(data, type, row, meta) {
        //     var json = JSON.stringify(row)
            
        //     var str = ``
        //     return str
        //   },
        // },
      ]
    })
  })
</script>
@endpush