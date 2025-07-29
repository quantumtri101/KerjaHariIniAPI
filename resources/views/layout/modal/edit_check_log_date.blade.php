<div class="modal fade" id="edit_check_log_date" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.edit_check_log_date') }}</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/check-log/edit') }}">
          @csrf
          <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>
          <input type="hidden" name="user_id" id="check_log_date_user_id"/>
          {{-- <input type="hidden" name="is_approve" value="1"/> --}}

          <div class="form-group">
            <label>{{ __('general.check_in_date') }}</label>
            <input type="text" id="checkindatetimepicker" name="check_in_date" class="form-control" data-toggle="datetimepicker" data-target="#checkindatetimepicker"/>
          </div>

          <div class="form-group">
            <label>{{ __('general.check_out_date') }}</label>
            <input type="text" id="checkoutdatetimepicker" name="check_out_date" class="form-control" data-toggle="datetimepicker" data-target="#checkoutdatetimepicker"/>
          </div>

          {{-- <div class="form-group">
            <label>{{ __('general.image') }}</label>
            @include('layout.upload_multiple_photo', [
              "column" => "file_name",
              "form_name" => "file[]",
              "accept" => "*/*",
              "id" => "check_log_document_image",
              "url_image" => "/image/check-log/document",
            ])
          </div> --}}

          <div>
            <button type="submit" class="btn btn-primary" >Submit</button>
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var checkindatetimepicker = null
    var checkoutdatetimepicker = null
    var jobsShiftMinDate = moment('{{ $jobs_shift->start_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')
    var jobsShiftMaxDate = moment('{{ $jobs_shift->end_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')

    function init_check_in_date(){
      if(checkindatetimepicker != null)
        $('#checkindatetimepicker').datetimepicker('destroy')
      console.log(jobsShiftMinDate)
      checkindatetimepicker = $('#checkindatetimepicker').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        useCurrent: false,
        defaultDate: moment($('#checkindatetimepicker').val(), 'DD-MM-YYYY HH:mm'),
        minDate: jobsShiftMinDate,
        maxDate: $('#checkoutdatetimepicker').val() != "" ? moment($('#checkoutdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment($('#checkindatetimepicker').val(), 'DD-MM-YYYY HH:mm').add(1, 'h'),
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#checkindatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_check_out_date()
        }
      })
    }

    function init_check_out_date(){
      if(checkoutdatetimepicker != null)
        $('#checkoutdatetimepicker').datetimepicker('destroy')
      checkoutdatetimepicker = $('#checkoutdatetimepicker').datetimepicker({
        format: 'DD-MM-YYYY HH:mm',
        useCurrent: false,
        defaultDate: $('#checkoutdatetimepicker').val() != "" ? moment($('#checkoutdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment($('#checkindatetimepicker').val(), 'DD-MM-YYYY HH:mm').add(1, 'h'),
        minDate: $('#checkindatetimepicker').val() != "" ? moment($('#checkindatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment($('#checkindatetimepicker').val(), 'DD-MM-YYYY HH:mm').add(1, 'h'),
        maxDate: jobsShiftMaxDate,
        icons: {
          time: 'fa-solid fa-clock',
          date: 'fa-solid fa-calendar',
        },
      })

      $('#checkoutdatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
        if(oldDate != null){
          init_check_in_date()
        }
      })
    }

    $(document).ready(function() {
      
    })
  </script>
@endpush
