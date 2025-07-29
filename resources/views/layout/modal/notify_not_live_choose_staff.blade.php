<div class="modal fade" id="notify_not_live_choose_staff" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.notice') }}</h5>
      </div>
      <div class="modal-body text-center">
        <p class="m-0 font-weight-bold" style="font-size: 1.3rem, color: #232D42">{!! str_replace('\n', '<br/>', __('general.choose_staff_not_live')) !!}</p>

        <div>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="not_live_question()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    function not_live_question(){
      $('#is_live_app').val(0)
      $('#jobsForm').trigger('submit')
    }

    var notify_not_live_choose_staff = new Vue({
      el: '#notify_not_live_choose_staff',
      data: {
      },
    })
  </script>
@endpush
