@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.salary_verification'),
    ],
    "title" => __('salary_verification.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('salary_verification.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/check-log/action') }}"  onclick="save_current_page('{{ __('salary_verification.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3 table-responsive">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.event_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.job_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.working_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_check_in') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_check_out') }}</th>
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
        // {
        //   className: 'btn btn-primary',   
        //   text : "{{ __('general.add') }}",
        //   action: function ( e, dt, node, config ) {
        //     save_current_page("{{ __('salary_verification.title') }}")
        //     location.href = "{{ url('/check-log/action') }}"
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
        url : `{{ url('api/jobs/shift') }}?is_approve=1&arr_type=["applicant_exist"]&is_approve_check_log=1`,
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
        {"data" : "event_name", name: "event.name"},
        {"data" : "jobs_name", name: "jobs1.name"},
        {"data" : "working_date_format", name: "jobs_shift.start_date"},
        {"data" : "total_check_in_format", name: "total_check_in"},
        {"data" : "total_check_out_format", name: "total_check_out"},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var str = ""
            str += '<div style="width: auto">'
              str += `<a class="btn btn-primary" onclick="save_current_page('{{ __('salary_verification.title') }}')" href="{{ url('/salary/detail') }}?id=${row.id}">{{ __('general.detail') }}</a>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush
