<div class="modal fade" id="rating_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.rating') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/rating') }}">
          @csrf
          <input type="hidden" name="jobs_id" id="rating_jobs_id" value="{{ $jobs->id }}" />

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.customer_oncall') }}</label>
            <select name="staff_id" class="form-control">
              <option value="">{{ __('general.choose_customer') }}</option>
              @foreach($arr_staff as $staff)
                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.rating') }}</label>
            <input type="text" name="rating" id="rating" class="form-control">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.review') }}</label>
            <textarea name="review" id="review" class="form-control"></textarea>
          </div>

          <div class="form-group">
            <button class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    $(document).ready(() => {
      $('#rating').keyup(() => {
        $('#rating').val(to_currency_format($('#rating').val()))
      })
    })
  </script>
@endpush
