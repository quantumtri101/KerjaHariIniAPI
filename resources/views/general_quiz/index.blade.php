@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.general_quiz'),
    ],
    "title" => __('general_quiz.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('general_quiz.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/general_quiz/action') }}"  onclick="save_current_page('{{ __('general_quiz.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_option') }}</th>
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
            save_current_page("{{ __('general_quiz.title') }}")
            location.href = "{{ url('/general-quiz/multiple') }}"
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
        url : "{{ url('api/general-quiz/question') }}",
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
        {"data" : "total_option", name: "total_option"},
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
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('general_quiz.title') }}')" href="{{ url('/general-quiz/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`
              if(row.allow_delete)
                str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/general-quiz/delete') }}?id=${row.id}')")>Delete</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
