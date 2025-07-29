<div class="card shadow-base bd-0 overflow-hidden h-100">
  <div class="d-flex flex-column h-100 ">
    <div class="pd-x-25 pd-t-25 flex-fill">
      <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1 mg-b-20">{{ __('general.total_pay_by_app') }}</h6>
      <h1 class="tx-56 tx-light tx-inverse mg-b-0">{{ $total_payment_via_app }}<span class="tx-teal tx-24">{{ __('general.point') }}</span></h1>
      <p>{{ __('general.equivalent_to_num', ["num" => number_format($total_payment_via_app_conversion, 0, ',', '.')]) }}</p>
    </div><!-- pd-x-25 -->
    <div class="bg-dark pd-x-25 pd-y-25 d-flex justify-content-between">
      <div class="tx-center">
        <h3 class="tx-lato tx-white mg-b-5">{{ number_format($total_event_reservation_by_app, 0, ',', '.') }} <span class="tx-light op-8 tx-20">{{ __('general.point') }}</span></h3>
        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase mg-b-0 tx-white-8">{{ __('general.via_event_reservation') }}</p>
      </div>
      <div class="tx-center">
        <h3 class="tx-lato tx-white mg-b-5">{{ number_format($total_table_reservation_by_app, 0, ',', '.') }} <span class="tx-light op-8 tx-20">{{ __('general.point') }}</span></h3>
        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase mg-b-0 tx-white-8">{{ __('general.via_table_reservation') }}</p>
      </div>
      <div class="tx-center">
        <h3 class="tx-lato tx-white mg-b-5">{{ number_format($total_pay_bill_by_app, 0, ',', '.') }} <span class="tx-light op-8 tx-20">{{ __('general.point') }}</span></h3>
        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase mg-b-0 tx-white-8">{{ __('general.via_pay_bill') }}</p>
      </div>
    </div>
  </div>
</div>