<table>
  <thead>
    <tr>
      <th>{{ __('general.customer_phone') }}</th>
      <th>{{ __('general.customer_destination_phone') }}</th>
      @if(empty($branch))
        <th>{{ __('general.branch_name') }}</th>
      @endif
      <th>{{ __('general.total_transfer') }}</th>
      <th>{{ __('general.created_date') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_transfer_transaction as $transfer_transaction)
      <tr>
        <td>{{ !empty($transfer_transaction->user) ? $transfer_transaction->user->phone : '-' }}</td>
        <td>{{ !empty($transfer_transaction->user_destination) ? $transfer_transaction->user_destination->phone : '-' }}</td>
        @if(empty($branch))
          <td>{{ $transfer_transaction->branch->name }}</td>
        @endif
        <td>{{ number_format($transfer_transaction->amount, 0, ',', '.') }} Point</td>
        <td>{{ $transfer_transaction->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
