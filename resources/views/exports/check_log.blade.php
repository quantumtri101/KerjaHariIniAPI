<table>
  <thead>
    <tr>
      <th>{{ __('general.event_name') }}</th>
      <th>{{ __('general.job_name') }}</th>
      <th>{{ __('general.start_working_date') }}</th>
      <th>{{ __('general.end_working_date') }}</th>
      <th>{{ __('general.total_check_in') }}</th>
      <th>{{ __('general.total_check_out') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_jobs_shift as $jobs_shift)
      <tr>
        <td>{{ $jobs_shift->jobs->event->name }}</td>
        <td>{{ $jobs_shift->jobs->name }}</td>
        <td>{{ $jobs_shift->start_date->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ $jobs_shift->end_date->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ $jobs_shift->total_check_in.'/'.$jobs_shift->total_applicant }}</td>
        <td>{{ $jobs_shift->total_check_out.'/'.$jobs_shift->total_applicant }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
