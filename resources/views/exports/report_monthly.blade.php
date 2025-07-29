<table>
  <thead>
    <tr>
      <th>{{ __('general.month') }}</th>
      <th>{{ __('general.total_expense') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_month as $month)
      <tr>
        <td>{{ $month->date_text }}</td>
        <td>Rp. {{ number_format($month['total_expense'], 0, ',', '.') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
