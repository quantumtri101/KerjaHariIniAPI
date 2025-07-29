<div>
  <div class="row">
    <div class="col-6">
      <p class="m-0">{{ __('general.last_update') }}: {{ $resume->updated_at->formatLocalized('%d/%m/%Y') }}</p>
    </div>
    <div class="col-6 text-right">
      <a class="btn btn-primary" href="{{ url('/export/resume?id='.$resume->id) }}" target="_blank">{{ __('general.download_resume') }}</a>
    </div>

    <div class="col-8 offset-md-2">
      <div class="mt-3 card">
        <div class="card-body">
          <div class="row bg-white">
            <div class="col-6">
              <div class="card">
                <div class="card-body">
                  <img src="{{ url('/image/user?file_name='.$resume->user->file_name) }}" style="width: 30rem; border-radius: 30rem;"/>
                  <p class="m-0">{{ $resume->name }}</p>
                  <p class="m-0">{{ $resume->phone }}</p>
                  <p class="m-0">{{ __('general.user_from', ["date" => $resume->created_at->formatLocalized('%d %B %Y')]) }}</p>
                </div>
              </div>

              <div class="mt-3">
                <div class="row">
                  <div class="col-12">
                    <p class="m-0">{{ __('general.personal_detail') }}</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.gender') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ __('general.'.($resume->gender == 1 ? 'male' : 'female')) }}</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.birth_date') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ $resume->birth_date->formatLocalized('%d %B %Y') }}</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.address') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ $resume->address }}</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.status') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ __('general.'.$resume->marital_status) }}</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.last_education') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ !empty($resume->last_education) ? $resume->last_education : '-' }}</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.height') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ $resume->height }} cm</p>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.weight') }}</p>
                  </div>
                  <div class="col-6 text-right">
                    <p class="m-0">{{ $resume->weight }} kg</p>
                  </div>
                </div>
              </div>

              <div class="mt-3">
                <div class="row">
                  <div class="col-12">
                    <p class="m-0">{{ __('general.skill') }}</p>
                  </div>
                  <div class="col-12">
                    @foreach($resume->skill as $key => $skill)
                      <p class="m-0">{{ $key + 1 }}. {{ !empty($skill->skill) ? $skill->skill->name : $skill->custom_skill }}</p>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <p class="m-0">{{ __('general.resume') }}</p>
              <div class="mt-3">
                <p class="m-0 mt-3">{{ __('general.work_experience') }}</p>
                @foreach($resume->experience as $key => $experience)
                  <div class="mt-1">
                    <p class="m-0">{{ $experience->name }}</p>
                    <p class="m-0">{{ $experience->start_year }} - {{ $experience->end_year }}</p>
                    <div class="d-flex align-items-center">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-building"></i>
                        <p class="m-0 ml-1">{{ $experience->corporation }}</p>
                      </div>

                      <div class="d-flex align-items-center ml-3">
                        <i class="fa-solid fa-location-dot"></i>
                        <p class="m-0 ml-1">{{ $experience->city->name }}</p>
                      </div>
                    </div>
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
                      <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center">
                          <i class="fa-solid fa-building"></i>
                          <p class="m-0 ml-1">{{ $jobs->user->corporation }}</p>
                        </div>

                        <div class="d-flex align-items-center ml-3">
                          <i class="fa-solid fa-location-dot"></i>
                          <p class="m-0 ml-1">{{ $jobs->city->name }}</p>
                        </div>
                      </div>
                      <p class="m-0">{{ $jobs->description }}</p>
                    </div>
                  @endforeach
                @else
                  <p class="m-0 mt-3">{{ __('general.not_working_yet') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>