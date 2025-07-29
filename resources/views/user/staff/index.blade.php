@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.staff'),
    ],
    "title" => __('staff.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('staff.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/user/staff/action') }}" onclick="save_current_page('{{ __('staff.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.email') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.phone') }}</th>
          @if(empty(Auth::user()->company))
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.company') }}</th>
          @endif
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.sub_category') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.position') }}</th>
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
    var arr_column = []
    @if(empty(Auth::user()->company))
      arr_column.push(
        {"data" : "id", "orderable" : false},
        {"data" : "name", name: "user.name"},
        {"data" : "email", name: "user.email"},
        {"data" : "phone", name: "user.phone"},
        {"data" : "company_name", name: "company.name"},
        {"data" : "sub_category_name", name: "sub_category.name"},
        {"data" : "company_position_name", name: "company_position.name"},
        {"data" : "created_at_format", name: "user.created_at"},
        {"data" : "updated_at_format", name: "user.updated_at"},
        {"data" : "action", "orderable" : false},
      )
    @else
      arr_column.push(
        {"data" : "id", "orderable" : false},
        {"data" : "name", name: "user.name"},
        {"data" : "email", name: "user.email"},
        {"data" : "phone", name: "user.phone"},
        {"data" : "sub_category_name", name: "sub_category.name"},
        {"data" : "company_position_name", name: "company_position.name"},
        {"data" : "created_at_format", name: "user.created_at"},
        {"data" : "updated_at_format", name: "user.updated_at"},
        {"data" : "action", "orderable" : false},
      )
    @endif

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
            save_current_page("{{ __('staff.title') }}")
            location.href = "{{ url('/user/staff/action') }}"
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
        url : "{{ url('api/user/staff') }}",
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
      "columns" : arr_column,
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var str = ""
            str += '<div style="width: 150px">'
            str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('staff.title') }}')" href="{{ url('/user/staff/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
            if(row.allow_delete)
              str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/api/user/staff/delete') }}?id=${row.id}', 'Are you sure to delete this data?--nOther data related this data will also be deleted')")>Delete</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
