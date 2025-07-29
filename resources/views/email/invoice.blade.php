@extends('layout.base_empty')
@section('content')
<div class="w-100">

	<section class="invoice">
		<div class="row">
      <div class="col-12">
        <h2 class="page-header" style="text-align: center">FAKTUR</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Qty</th>
              <th>Product</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Pembuatan Website</td>
              <td>Rp. {{ number_format(500000,2,',','.') }}</td>
              <td>Rp. {{ number_format(500000,2,',','.') }}</td>
            </tr>
            <tr>
              <th colspan="3" style="text-align: right;">Total:</th>
              <td>Rp. {{ number_format(500000,2,',','.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      {{-- <div class="col-12">
        <p>Pembayaran Cheque/Giro dianggap Lunas setelah Cair</p>
        <p>Kredit 45 hari</p>
        <p>Selama pembayaran blm lunas, barang masih berupa titipan</p>
      </div> --}}
      <div class="col-6">
        {{-- <h5 style="text-align: center">PENERIMA</h5> --}}
      </div>
      <div class="col-6 text-center">
        <h5 style="text-align: center">PROGRAMMER</h5>
				<img src="{{ $url_asset.'/image/jpg.jpg' }}" style="width: 10rem"/>
				<h5 style="text-align: center" class="mt-3">Joshua Buwono</h5>
      </div>
    </div>
	</section>

</div>
@endsection
