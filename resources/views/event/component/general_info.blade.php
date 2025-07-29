<div class="row">
  <div class="col-12 col-lg-8">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $event->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.name') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $event->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.start_date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $event->start_date->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.end_date') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $event->end_date->formatLocalized('%d %B %Y %H:%M') }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.company') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $event->company->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.company_phone') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $event->company->phone }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="form-group">
      <label for="exampleInputEmail1">{{ __('general.image') }}</label>
      <div>
        @foreach($event->image as $image)
          <img src="{{ url('/image/event?file_name='.$image->file_name) }}" width="50%"/>
        @endforeach
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    <a class="btn btn-primary" onclick="save_current_page('{{ __('event.detail') }}')" href="{{ url('/event/action?id='.$event->id) }}">{{ __('general.edit') }}</a>
  </div>
</div>