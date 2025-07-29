<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_salary_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_amount_salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.created_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.description') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  $(document).ready(function () {

    datatable = $('#list_salary_datatable').dataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{!! url('api/transaction/salary?user_id='.$customer_oncall->id) !!}",
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
        {"data" : "amount_format", name: "salary_transaction.amount"},
        {"data" : "date_format", name: "salary_transaction.date"},
        {"data" : "description", name: "salary_transaction.description"},
        {"data" : "status_approve_format", name: "salary_transaction.is_approve"},
      ],
    })
  })
</script>
@endpush