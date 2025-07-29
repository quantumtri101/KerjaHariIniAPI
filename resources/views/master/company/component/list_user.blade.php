<div>
  <div class="mt-3">
    <table class="table w-100" id="list_user_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.email') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.phone') }}</th>
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
    user_datatable = $('#list_user_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/user?company_id='.$company->id) }}",
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
        {"data" : "email", name: "email"},
        {"data" : "phone", name: "phone"},
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
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('company.detail') }}')" href="{{ url('/user/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush