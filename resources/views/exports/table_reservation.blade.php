<table>
  <thead>
    <tr>
      <th>{{ __('general.customer_phone') }}</th>
      @if(empty($branch))
        <th>{{ __('general.branch_name') }}</th>
      @endif
      <th>{{ __('general.table_name') }}</th>
      <th>{{ __('general.number_pax') }}</th>
      <th>{{ __('general.reserved_for') }}</th>
      <th>{{ __('general.order_at') }}</th>
      <th>{{ __('general.status') }}</th>
      <th>{{ __('general.check_in_date') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_reservation as $reservation)
      <tr>
        <td>{{ $reservation->user->phone }}</td>
        @if(empty($branch))
          <td>{{ $reservation->branch->name }}</td>
        @endif
        <td>{{ !empty($reservation->table_layout) ? $reservation->table_layout->table_no : '-' }}</td>
        <td>{{ $reservation->number_pax }}</td>
        <td>{{ $reservation->reserved_for->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ $reservation->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ __('general.'.$reservation->status) }}</td>
        <td>{{ !empty($reservation->check_in_at) ? $reservation->check_in_at->formatLocalized('%d %B %Y %H:%M') : '-' }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
