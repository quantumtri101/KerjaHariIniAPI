<div class="modal fade" id="notify_auto_live_choose_staff" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.notice') }}</h5>
      </div>
      <div class="modal-body text-center">
        <p class="m-0 font-weight-bold" style="font-size: 1.3rem, color: #232D42">{!! str_replace('\n', '<br/>', __('general.choose_staff_auto_live')) !!}</p>

        <div>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="auto_live_question(1)">Yes</button>
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" onclick="auto_live_question(0)">No</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    function auto_live_question(is_live_app){
      $('#is_live_app').val(is_live_app)
      if(is_live_app == 1)
        $('#publish_date_choose_staff').modal('show')
      else
        $('#jobsForm').trigger('submit')
    }

    var notify_auto_live_choose_staff = new Vue({
      el: '#notify_auto_live_choose_staff',
      data: {
      },
    })
  </script>
@endpush
