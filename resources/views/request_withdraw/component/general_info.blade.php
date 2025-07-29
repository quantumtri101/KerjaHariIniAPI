<div class="row">
  <div class="col-12">
    <div class="row">

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $request_withdraw->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.user_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $request_withdraw->user->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.bank_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $request_withdraw->bank->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.acc_no') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $request_withdraw->acc_no }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.acc_name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $request_withdraw->acc_name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.total_amount') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="Rp. {{ number_format($request_withdraw->total_amount, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status_approve') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.$request_withdraw->status) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      @if(!empty($request_withdraw->file_name))
        <div class="col-6 d-flex flex-column">
          <label for="exampleInputEmail1">Transfer Proof Image</label>
          <img src="{{ url('/image/request-withdraw').'?file_name='.$request_withdraw->file_name }}" style="width: 10rem;"/>
        </div>
      @endif

    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    @if($request_withdraw->status == 'requested')
      <button class="btn btn-primary" onclick="showApproveModal()">{{ __('general.set_approve') }}</button>
      <button class="btn btn-primary ml-3" onclick="showDeclineModal()">{{ __('general.set_decline') }}</button>
    @endif
  </div>
</div>

@include('layout.modal.decline_request_withdraw')
@include('layout.modal.set_approve_request_withdraw')

<script>
  function showDeclineModal(){
    $('#request_withdraw_decline_id').val('{{ $request_withdraw->id }}')
    $('#request_withdraw_decline_modal').modal('show')
  }

  function showApproveModal(){
    $('#request_withdraw_approve_id').val('{{ $request_withdraw->id }}')
    $('#request_withdraw_approve_modal').modal('show')
  }
</script>