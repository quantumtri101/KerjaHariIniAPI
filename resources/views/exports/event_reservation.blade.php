<table>
  <thead>
    <tr>
      <th>{{ __('general.customer_phone') }}</th>
      @if(!empty($event))
        @if($event->reservation_type == "by_app" || $event->reservation_type == "both")
          <th>{{ __('general.table_name') }}</th>
          <th>{{ __('general.number_pax') }}</th>
          <th>{{ __('general.payment') }}</th>
          <th>{{ __('general.order_at') }}</th>
          <th>{{ __('general.status') }}</th>
          <th>{{ __('general.check_in_date') }}</th>
        @else
          <th>{{ __('general.requested_at') }}</th>
        @endif
      @else
        <th>{{ __('general.event_name') }}</th>
        <th>{{ __('general.table_name') }}</th>
        <th>{{ __('general.number_pax') }}</th>
        <th>{{ __('general.payment') }}</th>
        <th>{{ __('general.order_at') }}</th>
        <th>{{ __('general.status') }}</th>
        <th>{{ __('general.check_in_date') }}</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach($arr_reservation as $reservation)
      <tr>
        <td>{{ $reservation->user->phone }}</td>
        @if(!empty($event))
          @if($event->reservation_type == "by_app" || $event->reservation_type == "both")
            <td>{{ $reservation->event_table_layout->table_layout->table_no }}</td>
            <td>{{ $reservation->number_pax }}</td>
            <td>{{ number_format($reservation->total_price, 0, '.', ',') }}</td>
            <td>{{ $reservation->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
            <td>{{ __('general.'.$reservation->status) }}</td>
            <td>{{ !empty($reservation->check_in_at) ? $reservation->check_in_at->formatLocalized('%d %B %Y %H:%M') : '-' }}</td>
          @else
            <td>{{ $reservation->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
          @endif
        @else
          <td>{{ $reservation->event->name }}</td>
          <td>{{ $reservation->event_table_layout->table_layout->table_no }}</td>
          <td>{{ $reservation->number_pax }}</td>
          <td>{{ number_format($reservation->total_price, 0, '.', ',') }}</td>
          <td>{{ $reservation->created_at->formatLocalized('%d %B %Y %H:%M') }}</td>
          <td>{{ __('general.'.$reservation->status) }}</td>
          <td>{{ !empty($reservation->check_in_at) ? $reservation->check_in_at->formatLocalized('%d %B %Y %H:%M') : '-' }}</td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
