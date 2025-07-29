<div class="card shadow-base bd-0 overflow-hidden">
  <div class="pd-x-25 pd-y-25">
    <div class="row">
      <div class="col-12">
        <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1 mg-b-20">{{ __('general.total_user') }}</h6>
      </div>

      <div class="col">
        <h1 class="tx-56 tx-light tx-inverse mg-b-0">{{ $total_male_user + $total_female_user }}<span class="tx-teal tx-24">{{ __('general.user') }}</span></h1>
      </div>

      <div class="col">
        <canvas id="chartBar" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var chart = null

  function init_chart(){
    var ctx = document.getElementById('chartBar').getContext('2d');
    var arr_label = []
    var arr_data = []
    var arr_background_color = []

    arr_label.push('{{ __("general.male") }}')
    arr_data.push({{ $total_male_user }})
    arr_background_color.push("#27AAC8")

    arr_label.push('{{ __("general.female") }}')
    arr_data.push({{ $total_female_user }})
    arr_background_color.push("#EAEAEA")

    // for(let data of arr){
    //   arr_label.push(data.branch.name)
    //   arr_data.push(data.total_price_top_up)
    // }

    if(chart != null)
      chart.destroy()

    chart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: arr_label,
        datasets: [{
          label: 'Total User',
          data: arr_data,
          backgroundColor: arr_background_color,
        }]
      },
      options: {
        legend: {
          display: false,
            labels: {
              display: false
            }
        },
        maintainAspectRatio: false,
      }
      
    });
  }

  $(document).ready(function () {
    init_chart()
  })
</script>
@endpush
