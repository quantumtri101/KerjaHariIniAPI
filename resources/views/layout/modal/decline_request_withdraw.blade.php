<div class="modal fade" id="request_withdraw_decline_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.request_withdraw_decline') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/request-withdraw/change-approve') }}">
          @csrf
          <input type="hidden" name="id" id="request_withdraw_decline_id"/>
          <input type="hidden" name="status" value="declined"/>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.decline_reason') }}</label>
            <textarea class="form-control" name="decline_reason" id="decline_reason"></textarea>
          </div>

          <div class="form-group">
            <button class="btn btn-danger" id="decline_button">Decline</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var request_withdraw_decline_modal = new Vue({
      el: '#request_withdraw_decline_modal',
      data: {
      },
    })
  </script>
@endpush
