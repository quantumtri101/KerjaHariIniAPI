@extends('exports.base_export')

@section('content')
	<div style="width: 45%; display: inline-block; margin-right: 30px">
		<div class="form-group ">
			<label>ID Top Up</label>
			<input type="text" class="form-control" readonly value="{{ $point_transaction->id }}" />
		</div>

		<div class="form-group ">
			<label>Total Top Up</label>
			<input type="text" class="form-control" readonly value="{{ number_format($point_transaction->total_price, 0, ',', '.') }}" />
		</div>

		<div class="form-group ">
			<label>Payment Method</label>
			<input type="text" class="form-control" readonly value="{{ $point_transaction->payment_method->name }}" />
		</div>

		@if($point_transaction->payment_method->data == "cash" && $point_transaction->status != "wait_payment")
			<div class="form-group ">
				<label>Payment to Cashier Type</label>
				<input type="text" class="form-control" readonly value="{{ $point_transaction->cash_cashier_type }}" />
			</div>
		@endif
	</div>

	<div style="width: 45%; display: inline-block; margin-left: 30px">
		<div class="form-group ">
			<label>Customer Phone</label>
			<input type="text" class="form-control" readonly value="{{ $point_transaction->user != null ? $point_transaction->user->phone : '-' }}" />
		</div>

		<div class="form-group ">
			<label>Total Top Up Point</label>
			<input type="text" class="form-control" readonly value="{{ number_format($point_transaction->amount, 0, ',', '.') }}" />
		</div>

		<div class="form-group ">
			<label>Created By, At</label>
			<input type="text" class="form-control" readonly value="{{ $point_transaction->created_at->formatLocalized('%d %B %Y %H:%M:%S') }}" />
		</div>
	</div>

	<div class="form-group ">
		<label>Status</label>
		<input type="text" class="form-control" readonly value="{{ __('general.'.$point_transaction->status) }}" />
	</div>

	<div class="form-group ">
		<label>Transaction Code</label>
		<input type="text" class="form-control" readonly value="{{ $point_transaction->transaction_code }}" />
	</div>

	<div class="form-group ">
		<label>Bonus Point</label>
		<input type="text" class="form-control" readonly value="{{ number_format($point_transaction->bonus, 0, ',', '.') }}" />
	</div>
@endsection
