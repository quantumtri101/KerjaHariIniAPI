@extends('exports.base_export')

@section('content')
<div>
  <p class="text-center">ABSENSI PERIODE {{ $jobs_shift->start_date->formatLocalized('%d %B %Y') }}</p>
  <p class="text-center">{{ $jobs_shift->jobs->name }}</p>
</div>

<div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th colspan="6" class="text-center">{{ $jobs_shift->start_date->formatLocalized('%d %B %Y') }}</th>
      </tr>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIK</th>
        <th>Nomor HP</th>
        <th>Masuk</th>
        <th>Keluar</th>
      </tr>
    </thead>
    <tbody>
      @if(count($arr_check_log) > 0)
        @foreach($arr_check_log as $key => $check_log)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $check_log->user->name }}</td>
            <td>{{ $check_log->user->id_no }}</td>
            <td>{{ $check_log->user->phone }}</td>
            <td>{{ $check_log->date->formatLocalized('%d %B %Y %H:%M') }}</td>
            <td>{{ $check_log->check_out->date->formatLocalized('%d %B %Y %H:%M') }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="6" class="text-center">{{ __('general.no_data') }}</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
@endsection