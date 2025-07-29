<div class="card">
  <div class="card-body">
    @if(count($arr_staff) > 0)
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rating_modal">
        {{ __('general.add') }}
      </button>
    @endif

    <div class="mt-3 table-responsive">
      <table class="table w-100" id="list_rating_datatable">
        <thead>
          <tr>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.user_name') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.rating') }}</th>
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.review') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@include('layout.modal.rating')

@push('script')
<script type="text/javascript">
  var rating_datatable = null

  $(document).ready(function () {
    rating_datatable = $('#list_rating_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/rating') }}?jobs_id={{ $jobs->id }}",
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
        {"data" : "staff_name", name: "staff.name"},
        {"data" : "rating", name: "rating"},
        {"data" : "review", name: "review"},
      ],
      "columnDefs" : [
        // {
        //   targets: -1,
        //   data: null,
        //   sorting: false,
        //   render: function(data, type, row, meta) {
        //     var json = JSON.stringify(row)
            
        //     var str = ""
        //     str += '<div style="">'
        //     str += '</div>'
        //     return str
        //   },
        // },
      ]
    })
  })
</script>
@endpush