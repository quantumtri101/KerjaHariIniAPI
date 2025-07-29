@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.ro'),
    ],
    "title" => __('ro.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('ro.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/user/ro/action') }}" onclick="save_current_page('{{ __('ro.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.company') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.email') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.phone') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.created_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.updated_at') }}</th>
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
    reset_page_stack()
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
            save_current_page("{{ __('ro.title') }}")
            location.href = "{{ url('/user/ro/action') }}"
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
        url : "{{ url('api/user/ro') }}",
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : [[5, "desc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "id", "orderable" : false},
        {"data" : "company_name", name: "company.name"},
        {"data" : "name", name: "user.name"},
        {"data" : "email", name: "user.email"},
        {"data" : "phone", name: "user.phone"},
        {"data" : "created_at_format", name: "user.created_at"},
        {"data" : "updated_at_format", name: "user.updated_at"},
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
            str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('ro.title') }}')" href="{{ url('/user/ro/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
            if(row.allow_delete)
              str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/api/user/ro/delete') }}?id=${row.id}', 'Are you sure to delete this data?--nOther data related this data will also be deleted')")>Delete</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
