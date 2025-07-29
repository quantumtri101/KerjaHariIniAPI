<div class="modal fade" id="please_wait_modal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.notice') }}</h5>
      </div>
      <div class="modal-body text-center">
        <p class="m-0 font-weight-bold" style="font-size: 1.3rem, color: #232D42">{{ __('general.please_wait') }}</p>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var please_wait_modal = new Vue({
      el: '#please_wait_modal',
      data: {
      },
    })
  </script>
@endpush
