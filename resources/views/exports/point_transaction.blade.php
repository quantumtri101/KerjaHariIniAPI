<table>
  <thead>
    <tr>
      <th>{{ __('general.customer_phone') }}</th>
      @if(empty($branch))
        <th>{{ __('general.branch_name') }}</th>
      @endif
      <th>{{ __('general.transaction_code') }}</th>
      <th>{{ __('general.total_top_up') }}</th>
      <th>{{ __('general.total_top_up_point') }}</th>
      <th>{{ __('general.bonus') }}</th>
      <th>{{ __('general.payment_method') }}</th>
      <th>{{ __('general.created_date') }}</th>
      <th>{{ __('general.status') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_point_transaction as $point_transaction)
      <tr>
        <td>{{ !empty($point_transaction->user) ? $point_transaction->user->phone : '-' }}</td>
        @if(empty($branch))
          <td>{{ $point_transaction->branch->name }}</td>
        @endif
        <td>{{ $point_transaction->transaction_code }}</td>
        <td>Rp. {{ number_format($point_transaction->total_price, 0, ',', '.') }}</td>
        <td>{{ number_format($point_transaction->total_price_point, 0, ',', '.') }} Point</td>
        <td>{{ number_format($point_transaction->bonus, 0, ',', '.') }} Point</td>
        <td>{{ !empty($point_transaction->payment_method) ? $point_transaction->payment_method->name.($point_transaction->payment_method->data == "cash" ? ' ('.__('general.'.$point_transaction->cash_cashier_from).')' : '') : '-' }}</td>
        <td>{{ $point_transaction->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ __('general.'.$point_transaction->status) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
