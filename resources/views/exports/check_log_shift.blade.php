
<table>
  <thead>
    <tr>
      <td colspan="7">ABSENSI KARYAWAN</td>
    </tr>
    <tr>
      <td colspan="7">{{ $jobs_shift->jobs->name }}</td>
    </tr>
    <tr>
      <td colspan="7">Periode {{ $jobs_shift->start_date->formatLocalized('%d %B').' - '.$jobs_shift->end_date->formatLocalized('%d %B %Y') }}</td>
    </tr>
    <tr>
      <th>No</th>
      <th>NIK KTP</th>
      <th>Nama</th>
      <th>Nomor HP</th>
      <th>Jabatan</th>
      <th>In</th>
      <th>Out</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_check_log as $key => $check_log)
      <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $check_log->user->id_no }}</td>
        <td>{{ $check_log->user->name }}</td>
        <td>{{ $check_log->user->phone }}</td>
        <td>{{ __('general.'.$check_log->user->type->name) }}</td>
        <td>{{ $check_log->date->formatLocalized('%d %B %Y %H:%M') }}</td>
        <td>{{ $check_log->check_out->date->formatLocalized('%d %B %Y %H:%M') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
