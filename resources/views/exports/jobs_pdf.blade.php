@extends('exports.base_export')

@section('content')
<div class="">
  <table class="w-100 h-auto">
    <tbody>
      <tr>
        <td>
          <h3 class="">MEMO</h3>
        </td>
        <td colspan=""></td>
        <td>
          <div class="text-right">
            <img src="{{ url('/image/company').'?file_name='.$jobs->company->file_name }}" style="width: 10rem">
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <div style="border: 1px solid black"></div>
</div>

<div class="mt-4">
  <p>To: {{ $jobs->approve[0]->user->name.' '.$jobs->approve[0]->user->company_position->name }}</p>
  <p>From: {{ $jobs->user->name }}</p>
  <p>CC: {{ $jobs->cc_string }}</p>
  <p>Date: {{ $jobs->created_at->formatLocalized('%d %B %Y') }}</p>
</div>

<div class="mt-5">
  <div style="border: 1px solid black; margin-bottom: .5rem;"></div>
  <div >
    <table class="w-100">
      <tbody>
        <tr>
          <td >
            <div class="d-inline-block w-100">
              <div class="d-inline-block" style="border-radius: 50%; width: 1rem; height: 1rem; background-color: {{ $jobs->is_urgent == 1 ? 'black' : 'white' }}; border: {{ $jobs->is_urgent == 1 ? '0px' : '1px' }} solid black; margin-bottom: .2rem;"></div>
              <h5 class="d-inline-block ml-3" style="margin-bottom: 0rem;">Urgent</h5>
            </div>
          </td>
          <td >
            <div class=" w-100">
              <div class="d-inline-block" style="border-radius: 50%; width: 1rem; height: 1rem; background-color: {{ $jobs->is_urgent == 0 ? 'black' : 'white' }}; border: {{ $jobs->is_urgent == 0 ? '0px' : '1px' }} solid black; margin-bottom: .2rem;"></div>
              <h5 class="d-inline-block ml-3" style="margin-bottom: 0rem;">Not Urgent</h5>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div style="border: 1px solid black"></div>
</div>

<div class="mt-5">
  <p>
    Dengan Hormat,<br/>
    Kami mohon bantuannya untuk. pengadaan {{ number_format($jobs->num_people_required, 0, ',', '.') }} orang tenaga casual yang kami perbantukan untuk sebagai berikut :
  </p>
  <ul>
    <li>Nama Event: {{ $jobs->event->name }}</li>
    <li>Nama Job: {{ $jobs->name }}</li>
    <li>Jumlah Kebutuhan: {{ number_format($jobs->num_people_required, 0, ',', '.') }} orang</li>
    <li>Tanggal Event: {{ $jobs->event->created_at->formatLocalized('%d %B %Y') }}</li>
    <li>Venue: {{ $jobs->company->address }}</li>
  </ul>
</div>

<div>
  <p>Demikian atas kerjasamanya kami ucapkan terima kasih,</p>
</div>

<div class="">
  <table class="w-100">
    <tbody>
      <tr>
        <td>
          <div>
            <div class="text-center">
              <p>Prepared By,</p>
              <p class="mt-5">{{ $jobs->user->name }}</p>
            </div>
          </div>
        </td>
        <td colspan=""></td>
        <td>
          <div class="text-right">
            <div class="text-center">
              <p>Approved By,</p>
              <p class="mt-5 mb-0">{{ $jobs->approve[0]->user->name }}</p>
              <p class="">{{ $jobs->approve[0]->user->company_position->name }}</p>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
@endsection