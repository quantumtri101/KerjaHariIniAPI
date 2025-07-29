@extends('exports.base_export')

@section('content')
<table style="margin-top: -100px;">
	<tr>
		<td style="width: 45%; display: inline-block; vertical-align: top;">
			<div class="">
				<img src="{{ url('/image/user?file_name='.$resume->user->file_name) }}" style="width: 10rem; border-radius: 30rem;"/>
				<p class="m-0">{{ $resume->name }}</p>
				<p class="m-0">{{ $resume->phone }}</p>
				<p class="m-0">{{ __('general.user_from', ["date" => $resume->created_at->formatLocalized('%d %B %Y')]) }}</p>
			</div>

			<div class="mt-3">
				<div class="row">
					<div style="width: 100%; display: inline-block;">
						<p class="m-0">{{ __('general.personal_detail') }}</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.gender') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ __('general.'.($resume->gender == 1 ? 'male' : 'female')) }}</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.birth_date') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ $resume->birth_date->formatLocalized('%d %B %Y') }}</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.address') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ $resume->address }}</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.status') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ __('general.'.$resume->marital_status) }}</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.last_education') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ !empty($resume->last_education) ? $resume->last_education : '-' }}</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.height') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ $resume->height }} cm</p>
					</div>
					<div style="width: 45%; display: inline-block;">
						<p class="m-0">{{ __('general.weight') }}</p>
					</div>
					<div style="width: 45%; display: inline-block; align: right">
						<p class="m-0">{{ $resume->weight }} kg</p>
					</div>
				</div>
			</div>

			<div class="mt-3">
				<div class="row">
					<div style="width: 100%; display: inline-block;">
						<p class="m-0">{{ __('general.skill') }}</p>
					</div>
					<div style="width: 100%; display: inline-block;">
						@foreach($resume->skill as $key => $skill)
							<p class="m-0">{{ $key + 1 }}. {{ !empty($skill->skill) ? $skill->skill->name : $skill->custom_skill }}</p>
						@endforeach
					</div>
				</div>
			</div>
		</td>
		<td style="width: 45%; display: inline-block; vertical-align: top;">
			<p class="m-0">{{ __('general.resume') }}</p>
			<div class="mt-3">
				<p class="m-0 mt-3">{{ __('general.work_experience') }}</p>
				@foreach($resume->experience as $key => $experience)
					<div class="mt-1">
						<p class="m-0">{{ $experience->name }}</p>
						<p class="m-0">{{ $experience->start_year }} - {{ $experience->end_year }}</p>
						<table>
							<tr>
								<td>
									<i class="fa-solid fa-building"></i>
								</td>
								<td>
									<p class="m-0 ml-1">{{ $experience->corporation }}</p>
								</td>

								<td class="">
									<div class="ml-3">
										<i class="fa-solid fa-location-dot"></i>
									</div>
								</td>
								<td class="">
									<p class="m-0 ml-1">{{ $experience->city->name }}</p>
								</td>
							</tr>
						</table>
						<p class="m-0">{{ $experience->description }}</p>
					</div>
				@endforeach
			</div>

			<div class="mt-3">
				<p class="m-0 mt-3">{{ __('general.work_experience_via_app') }}</p>
				@if(count($arr_jobs) > 0)
					@foreach($arr_jobs as $key => $jobs)
						<div class="mt-1">
							<p class="m-0">{{ $jobs->name }}</p>
							<p class="m-0">{{ $jobs->start_at->formatLocalized('%d %B %Y') }} - {{ $jobs->end_at->formatLocalized('%d %B %Y') }}</p>
							<table >
								<tr>
									<td>
										<i class="fa-solid fa-building"></i>
									</td>
									<td>
										<p class="m-0 ml-1">{{ $jobs->user->corporation }}</p>
									</td>
	
									<td class="">
										<div class="ml-3">
											<i class="fa-solid fa-location-dot"></i>
										</div>
									</td>
									<td class="">
										<p class="m-0 ml-1">{{ $jobs->city->name }}</p>
									</td>
								</tr>
							</table>
							<p class="m-0">{{ $jobs->description }}</p>
						</div>
					@endforeach
				@else
					<p class="m-0 mt-3">{{ __('general.not_working_yet') }}</p>
				@endif
			</div>
		</td>
	</tr>
</div>
@endsection
