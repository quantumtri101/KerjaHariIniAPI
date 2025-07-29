@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.request_withdraw'),
    ],
    "title" => __('request_withdraw.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('request_withdraw.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/request-withdraw/action') }}"  onclick="save_current_page('{{ __('request_withdraw.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.user_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_amount_salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.created_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('layout.modal.decline_request_withdraw')
@include('layout.modal.set_approve_request_withdraw')
@endsection

@push('script')
<script type="text/javascript">
  function showDeclineModal(request_withdraw_id){
    $('#request_withdraw_decline_id').val(request_withdraw_id)
    $('#request_withdraw_decline_modal').modal('show')
  }

  function showApproveModal(request_withdraw_id){
    $('#request_withdraw_approve_id').val(request_withdraw_id)
    $('#request_withdraw_approve_modal').modal('show')
  }

  $(document).ready(async function () {
    
    datatable = $('#datatable').dataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      dom: 'Bfrtip',
      buttons: [
        // {
        //   className: 'btn btn-primary',   
        //   text : "{{ __('general.add') }}",
        //   action: function ( e, dt, node, config ) {
        //     save_current_page('{{ __('request_withdraw.title') }}')
        //     location.href = "{{ url('/jobs/recommendation/action') }}"
        //   },
        //   init: function(api, node, config) {
        //     $(node).removeClass('dt-button')
        //   },
        // },
      ],
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/request-withdraw') }}",
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
        {"data" : "user_name", name: "user.name"},
        {"data" : "total_amount_format", name: "total_amount"},
        {"data" : "date_format", name: "date"},
        {"data" : "status_approve_format", name: "is_approve"},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var str = ""
            str += '<div>'
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('request_withdraw.title') }}')" href="{{ url('/request-withdraw/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
              if(row.status == 'requested'){
                str += `
                  <button class="btn btn-primary" onclick="showApproveModal('${row.id}')">{{ __('general.set_approve') }}</button>
                  <button class="btn btn-primary ml-1" onclick="showDeclineModal('${row.id}')">{{ __('general.set_decline') }}</button>

                  <a class="btn btn-danger ml-1 d-inline-block" href="#!" onclick="alertDelete('{{ url('/request-withdraw/delete') }}?id=${row.id}')">Delete</a>
                `
              }
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
