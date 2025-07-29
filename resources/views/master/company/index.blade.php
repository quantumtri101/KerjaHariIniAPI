@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.company'),
    ],
    "title" => __('company.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('company.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/company/action') }}"  onclick="save_current_page('{{ __('company.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.city_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.category_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.phone') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.address') }}</th>
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
            save_current_page("{{ __('company.title') }}")
            location.href = "{{ url('/master/company/action') }}"
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
        url : "{{ url('api/company') }}",
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : [[2, "asc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "city_name", name: "city.name"},
        {"data" : "category_name", name: "category.name"},
        {"data" : "name", name: "name"},
        {"data" : "phone", name: "phone"},
        {"data" : "address", name: "address"},
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
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('company.title') }}')" href="{{ url('/master/company/detail') }}?id=${row.id}">{{ __('general.detail') }}</a>`
              if(row.allow_delete)
                str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/master/company/delete') }}?id=${row.id}')">Delete</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
