<div class="rounded overflow-hidden {{ !empty($bg_color) ? $bg_color : '' }}">
  <div class="pd-20 d-flex align-items-center">
    <i class="ion {{ !empty($icon) ? $icon : 'ion-earth' }} tx-60 lh-0 tx-white op-7"></i>
    <div class="mg-l-20">
      <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">{{ $title }}</p>
      <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1" id="{{ !empty($value_id) ? $value_id : '' }}">{{ $value }}</p>
    </div>
  </div>
</div>

{{-- <div class="card">
  <div class="card-body">
    <div class="d-flex align-items-center">
      <h3 class="font-weight-bold m-0">{{ $value }}</h3>
      <p class="m-0 ml-3">{{ $title }}</p>
    </div>
  </div>
</div> --}}