<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_request_withdraw_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_amount') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.created_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_approve') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  $(document).ready(function () {

    datatable = $('#list_request_withdraw_datatable').dataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{!! url('api/request-withdraw?user_id='.$customer_regular->id) !!}",
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : [[2, "desc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "id", "orderable" : false, },
        {"data" : "total_amount_format", name: "total_amount"},
        {"data" : "date_format", name: "date"},
        {"data" : "status_approve_format", name: "is_approve"},
      ],
    })
  })
</script>
@endpush