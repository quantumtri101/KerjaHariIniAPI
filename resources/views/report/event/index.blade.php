@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.report_event'),
    ],
    "title" => __('report_event.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('report_event.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/check-log/action') }}"  onclick="save_current_page('{{ __('report_event.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="card">
    <div class="card-body">
      <canvas id="chartBar1" height="50"></canvas>
    </div>
  </div>

  <div class="mt-3 table-responsive">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.event_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_customer_regular') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_customer_oncall') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_budget') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_expense') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
  $(document).ready(async function () {
    var ctx = document.getElementById('chartBar1').getContext('2d');
    var arr_label = []
    var arr_datasets = []
    var arr_total_expense = []
    var arr_total_budget = []

    @foreach($arr as $event)
      arr_label.push('{{ $event->name }}')
      arr_total_expense.push({{ $event->total_expense }})
      arr_total_budget.push({{ $event->total_budget }})
    @endforeach
    arr_datasets.push(
      {
        label: '{{ __('general.total_expense') }}',
        data: arr_total_expense,
        backgroundColor: '#27AAC8',
      },
      {
        label: '{{ __('general.total_budget') }}',
        data: arr_total_budget,
        backgroundColor: '#FF0000',
      },
    )

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: arr_label,
        datasets: arr_datasets
      },
      options: {
        legend: {
          display: false,
            labels: {
              display: false
            }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true,
              fontSize: 10,
              max: 80
            }
          }],
          xAxes: [{
            ticks: {
              beginAtZero:true,
              fontSize: 11
            }
          }]
        }
      }
    });

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
        //     save_current_page("{{ __('report_event.title') }}")
        //     location.href = "{{ url('/check-log/action') }}"
        //   },
        //   init: function(api, node, config) {
        //     $(node).removeClass('dt-button')
        //   },
        // },
        // {
        //   className: 'btn btn-primary',   
        //   text : "{{ __('general.export') }}",
        //   action: function ( e, dt, node, config ) {
        //     location.href = "{{ url('/check-log/export') }}"
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
        url : `{{ url('api/report/event') }}`,
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
        {"data" : "name", name: "event.name"},
        {"data" : "total_applicant_regular", name: "total_applicant_regular"},
        {"data" : "total_applicant_oncall", name: "total_applicant_oncall"},
        {"data" : "total_budget_format", name: "total_budget"},
        {"data" : "total_expense_format", name: "total_expense"},
      ],
      "columnDefs" : [
      ]
    })
  })
</script>
@endpush
