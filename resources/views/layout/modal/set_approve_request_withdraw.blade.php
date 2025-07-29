<div class="modal fade" id="request_withdraw_approve_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.request_withdraw_approve') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/request-withdraw/change-approve') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" id="request_withdraw_approve_id"/>
          <input type="hidden" name="status" value="accepted"/>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.transfer_date') }}</label>
            <input type="text" name="transfer_date" id="transfer_date_datepicker" class="form-control" data-toggle="datetimepicker" class="form-control">
          </div>

          <div class="form-group">
            <label>{{ __('general.image') }}</label>
            @include('layout.upload_photo', [
              "column" => "file_name",
              "form_name" => "file",
              "accept" => "*/*",
              "id" => "request_withdraw_image",
              "url_image" => "/image/request-withdraw",
            ])
          </div>

          <div class="form-group">
            <button class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    $(document).ready(() => {
      $('#transfer_date_datepicker').datetimepicker({
        format: 'DD/MM/YYYY',
        useCurrent: false,
        minDate: moment().startOf('day'),
      })
    })
  </script>
@endpush
