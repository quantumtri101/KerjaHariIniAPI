<table>
  <thead>
    <tr>
      <th>{{ __('general.event_name') }}</th>
      <th>{{ __('general.total_customer_regular') }}</th>
      <th>{{ __('general.total_customer_oncall') }}</th>
      <th>{{ __('general.total_budget') }}</th>
      <th>{{ __('general.total_expense') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_check_log as $check_log)
      <tr>
        <td>{{ $check_log->name }}</td>
        <td>{{ $check_log->total_applicant_regular }}</td>
        <td>{{ $check_log->total_applicant_oncall }}</td>
        <td>{{ $check_log->total_budget_format }}</td>
        <td>{{ $check_log->total_expense_format }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
