@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.report_monthly'),
    ],
    "title" => __('report_monthly.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('report_monthly.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/check-log/action') }}"  onclick="save_current_page('{{ __('report_monthly.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="card">
    <div class="card-body">
      <canvas id="chartBar1" height="50"></canvas>
    </div>
  </div>

  <div class="mt-3 table-responsive">
    <table class="table table-bordered bg-white" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.month') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.total_expense') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($arr_month as $month)
          <tr>
            <td>{{ $month['date_text'] }}</td>
            <td>Rp. {{ number_format($month['total_expense'], 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
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
    var arr = []

    @foreach($arr_month as $month)
      arr_label.push('{{ $month['date_text'] }}')
      arr.push({{ $month['total_expense'] }})
    @endforeach
    arr_datasets.push(
      {
        label: '{{ __('general.total_expense') }}',
        data: arr,
        backgroundColor: '#27AAC8'
      }
    )

    new Chart(ctx, {
      type: 'line',
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
  })
</script>
@endpush
