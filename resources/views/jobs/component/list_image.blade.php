<div>
  {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editQualification" onclick="set_image_data_modal()">
    {{ __('general.add') }}
  </button> --}}

  @if(count($jobs->image) > 0)
    <div class="row">
      @foreach($jobs->image as $image)
        <div class="col-6 col-lg-3 mt-3">
          <img src="{{ url('/image/jobs').'?file_name='.$image->file_name }}" style="width: 10rem"/>
        </div>
      @endforeach
    </div>
  @else
    <div class="d-flex align-items-center justify-content-center">
      <p class="m-0">{{ __('general.no_image') }}</p>
    </div>
  @endif
</div>