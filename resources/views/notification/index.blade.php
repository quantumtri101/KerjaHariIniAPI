@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.notification'),
    ],
    "title" => __('notification.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('notification.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/notification/action') }}" onclick="save_current_page('{{ __('notification.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3 table-responsive">
    <table class="table w-100" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.schedule') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.message') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.sent_to') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.created_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
  $(document).ready(function () {
    datatable = $('#datatable').dataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      dom: 'Bfrtip',
      buttons: [
        {
          className: 'btn btn-primary',   
          text : "{{ __('general.add') }}",
          action: function ( e, dt, node, config ) {
            save_current_page("{{ __('notification.title') }}")
            location.href = "{{ url('/notification/action') }}"
          },
          init: function(api, node, config) {
            $(node).removeClass('dt-button')
          },
        },
      ],
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/communication') }}",
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : [[4, "desc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "id", "orderable" : false},
        {"data" : "scheduled_at_format", name: "communication.scheduled_at"},
        {"data" : "title", name: "communication.title"},
        {"data" : "sent_to_format",},
        {"data" : "created_at_format", name: "communication.created_at"},
        {"data" : "status", name: "communication.status"},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var str = ""
            str += '<div style="width: 150px">'
            str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('notification.title') }}')" href="{{ url('/notification/detail') }}?id=${row.id}">{{ __('general.detail') }}</a>`
            if(row.status === 'pending')
              str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/api/communication/delete') }}?id=${row.id}')")>{{ __('general.cancel') }}</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
