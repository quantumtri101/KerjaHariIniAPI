@extends('layout.base_empty')

@section('content')
	<h5>{{ __('general.request_account_deleted') }}</h5>

	<form method="post" id="action" action="{{ url('/user/request-delete') }}" enctype="multipart/form-data">
		@csrf

		<div class="row">
			<div class="col-12 mb-3">
				<div class="form-group">
					<label>{{ __('general.email') }}</label>
					<input type="text" required name="email" class="form-control"/>
				</div>
			</div>

			<div class="col-12 form-group">
				<button class="btn btn-primary">{{ __('general.submit') }}</button>
			</div>
		</div>
	</form>
@endsection
