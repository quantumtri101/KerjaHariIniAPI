@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.event'),
    ],
    "title" => __('event.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('event.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/event/action') }}"  onclick="save_current_page('{{ __('event.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3 table-responsive">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.company_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
  $(document).ready(async function () {
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
            save_current_page("{{ __('event.title') }}")
            location.href = "{{ url('/event/action') }}"
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
        url : "{{ url('api/event') }}",
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
        {"data" : "company_name", name: "company.name"},
        {"data" : "name", name: "name"},
        {"data" : "date_format", name: "start_date"},
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
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('event.title') }}')" href="{{ url('/event/detail') }}?id=${row.id}">{{ __('general.detail') }}</a>`
              @if(Auth::user()->type->name == "RO" || Auth::user()->type->name == "admin")
                if(row.allow_delete)
                  str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/event/delete') }}?id=${row.id}')">Delete</a>`
              @endif
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
