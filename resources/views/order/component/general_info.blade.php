<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $order->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $order->date->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.expected_end_date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $order->expected_end_date->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.sub_category') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $order->sub_category->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.location') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $order->location }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.detail_location') }}</label>
          <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $order->detail_location }}</textarea>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.duration') }}</label>
          <div class="input-group">
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $order->detail_duration }}" aria-describedby="emailHelp" disabled>
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon1">{{ __('general.minute') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.total_price') }}</label>
          <div class="input-group">
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
            </div>
            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($order->total_price, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.$order->status) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender_customer') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($order->gender_customer == 1 ? 'male' : 'female')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.gender_theraphyst') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($order->gender_theraphyst == 1 ? 'male' : 'female')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    <a class="btn btn-primary" onclick="save_current_page('{{ __('order.detail') }}')" href="{{ url('/order/action?id='.$order->id) }}">{{ __('general.edit') }}</a>
  </div>
</div>