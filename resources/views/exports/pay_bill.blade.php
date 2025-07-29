<table>
  <thead>
    <tr>
      <th>{{ __('general.customer_phone') }}</th>
      @if(empty($branch))
        <th>{{ __('general.branch_name') }}</th>
      @endif
      <th>{{ __('general.total_payment') }}</th>
      <th>{{ __('general.level_member') }}</th>
      <th>{{ __('general.created_date') }}</th>
      <th>{{ __('general.status') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_pay_bill as $pay_bill)
      <tr>
        <td>{{ !empty($pay_bill->user) ? $pay_bill->user->phone : '-' }}</td>
        @if(empty($branch))
          <td>{{ $pay_bill->branch->name }}</td>
        @endif
        <td>{{ $pay_bill->total_price_point }} Point</td>
        <td>{{ !empty($pay_bill->user) ? $pay_bill->user->member->name : '-' }}</td>
        <td>{{ $pay_bill->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ __('general.'.$pay_bill->status_payment) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
