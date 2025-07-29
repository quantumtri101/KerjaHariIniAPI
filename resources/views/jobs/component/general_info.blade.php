<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.id') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->id }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.name') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->name }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.user_name') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->user->name }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.category_name') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->sub_category->category->name }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.sub_category_name') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->sub_category->name }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.event_name') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->event->name }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.description') }}</label>
              <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $jobs->description }}</textarea>
            </div>
          </div>

          {{-- <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.benefit') }}</label>
              <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $jobs->benefit }}</textarea>
            </div>
          </div> --}}

          {{-- <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.status_publish') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($jobs->is_publish == 1 ? 'publish' : 'not_publish')) }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.status_approve') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($jobs->is_approve == 1 ? 'approve' : 'not_approve')) }}" aria-describedby="emailHelp" disabled>
            </div>
          </div> --}}

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.status_urgent') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($jobs->is_urgent == 1 ? 'urgent' : 'not_urgent')) }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.type') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.$jobs->staff_type) }}" aria-describedby="emailHelp" disabled>
            </div>
          </div>

          {{-- <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.status') }}</label>
              <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.$jobs->status) }}" aria-describedby="emailHelp" disabled>
            </div>
          </div> --}}

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.salary_regular') }}</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp.</span>
                </div>
                <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($jobs->salary_regular, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
                <div class="input-group-append {{ $jobs->salary_type_regular == "per_hour" ? '' : 'd-none' }}">
                  <span class="input-group-text" id="basic-addon1">/ Hour</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.salary_casual') }}</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp.</span>
                </div>
                <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($jobs->salary_casual, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
                <div class="input-group-append {{ $jobs->salary_type_casual == "per_hour" ? '' : 'd-none' }}">
                  <span class="input-group-text" id="basic-addon1">/ Hour</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.benefit') }}</label>
              <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" disabled>{{ $jobs->benefit }}</textarea>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.num_people_required') }}</label>
              <div class="input-group">
                <input type="text" class="form-control" id="exampleInputEmail1" value="{{ number_format($jobs->num_people_required, 0, ',', '.') }}" aria-describedby="emailHelp" disabled>
                <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon1">Person</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.status_on_app') }}</label>
              <div>
                <span class="bg-{{ $jobs->is_available_shift && $jobs->is_live_app == 1 ? 'success' : 'danger' }} pd-y-3 pd-x-10 tx-white tx-11 tx-roboto">{{ __('general.'.($jobs->is_available_shift ? ($jobs->is_live_app == 1 ? 'live' : 'not_live') : 'ended')) }}</span>
              </div>
            </div>
          </div>

          @if(!empty($jobs->publish_start_at))
            <div class="col-12 col-lg-6">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ __('general.publish_start_date') }}</label>
                <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->publish_start_at->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
              </div>
            </div>
          @endif

          @if(!empty($jobs->publish_end_at))
            <div class="col-12 col-lg-6">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ __('general.publish_end_date') }}</label>
                <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $jobs->publish_end_at->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="col-12 d-flex mt-3">
        @if(Auth::user()->type->name == "admin" || Auth::user()->type->name == "RO")
          @if($jobs->is_available_shift)
            <a class="btn btn-primary mr-3" onclick="save_current_page('{{ __('jobs.detail') }}')" href="{{ url('/jobs/action?id='.$jobs->id) }}">{{ __('general.edit') }}</a>
          @endif

          {{-- <a class="btn btn-primary" target="_blank" href="{{ url('/jobs/print-qr?id='.$jobs->id) }}">{{ __('general.print_qr') }}</a> --}}
          

          @if($jobs->is_approve == 1 && $jobs->shift[0]->start_date > \Carbon\Carbon::now())
            @if($jobs->is_live_app == 1)
              <form method="post" action="{{ url('/jobs/change-live') }}" class="d-inline-block ml-3">
                @csrf
                <input type="hidden" name="jobs_id" value="{{ $jobs->id }}"/>
                <input type="hidden" name="is_live_app" value="0"/>
                <button class="btn btn-primary">{{ __('general.change_not_live') }}</button>
              </form>
            @else
              <form method="post" id="jobsForm" action="{{ url('/jobs/change-live') }}" class="d-inline-block ml-3">
                @csrf
                <input type="hidden" name="jobs_id" value="{{ $jobs->id }}"/>
                <input type="hidden" name="publish_start_date" id="publish_start_date"/>
                <input type="hidden" name="publish_end_date" id="publish_end_date"/>
              </form>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#publish_date_choose_staff">{{ __('general.change_live') }}</button>
            @endif
          @endif
        @endif
      </div>
    </div>
  </div>
</div>

@if($jobs->is_approve == 1 && $jobs->shift[0]->start_date > \Carbon\Carbon::now())
  @include('layout.modal.publish_date_choose_staff', [
    "type" => "detail",
  ])
@endif

@push('script')
<script type="text/javascript">
  
</script>
@endpush