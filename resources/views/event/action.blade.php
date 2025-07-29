@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.event'),
      __('event.edit'),
    ] : [
      __('general.event'),
      __('event.add'),
    ],
    "title" => Request::has('id') ? __('event.edit') : __('event.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('event.edit') : __('event.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" action="{{ url(Request::has('id') ? '/event/edit' : '/event') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

        <div class="form-group">
          <label>{{ __('general.company') }}</label>
          <select required name="company_id" class="form-control"  {{ Auth::user()->type->name == "RO" || Auth::user()->type->name == "staff" ? 'disabled' : '' }}>
            @foreach($arr_company as $company)
              <option value="{{ $company->id }}" {{ (Auth::user()->type->name != "RO" && Auth::user()->type->name != "staff" && !empty($event) && $event->company->id == $company->id) || ((Auth::user()->type->name == "RO" || Auth::user()->type->name == "staff") && Auth::user()->company->id == $company->id) ? 'selected' : '' }}>{{ $company->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>{{ __('general.name') }}</label>
          <input type="text" required name="name" class="form-control" value="{{ !empty($event) ? $event->name : '' }}"/>
        </div>

        <div class="form-group">
          <label>{{ __('general.start_date') }}</label>
          <div id="startdatetimepickererror">
            <label >{{ __('general.datepicker_error') }}</label>
          </div>
          <input type="text" name="start_date" id="startdatetimepicker" class="form-control" data-toggle="datetimepicker"/>
        </div>
      
        <div class="form-group">
          <label>{{ __('general.end_date') }}</label>
          <div id="enddatetimepickererror">
            <label >{{ __('general.datepicker_error') }}</label>
          </div>
          <input type="text" name="end_date" id="enddatetimepicker" class="form-control" data-toggle="datetimepicker" />
        </div>

        <div class="form-group">
          <label>{{ __('general.image') }}</label>
          @include('layout.upload_multiple_photo', [
            "column" => "file_name",
            "form_name" => "image[]",
            "data" => $event,
            "id" => "event_image",
            "url_image" => "/image/event",
          ])
        </div>

        <div class="form-group" >
          <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
          <button class="btn btn-primary" id="submit">{{ __('general.submit') }}</button>
        </div>
      </form>
    </div>
  </div>

  @push('script')
    <script>
      var startDateFirstTime = true
      var endDateFirstTime = true
      var startDateFirstTime1 = true
      var endDateFirstTime1 = true

      function init_start_date(){
        if(!startDateFirstTime){
          $('#startdatetimepicker').datetimepicker('destroy')

        }
        startDateFirstTime = false
        $('#startdatetimepickererror').addClass('d-none')
        $('#startdatetimepicker').removeClass('d-none')
        var start_date = '{{ !empty($event) ? $event->start_date->formatLocalized('%d-%m-%Y %H:%M') : '' }}'
        
        try{
          $('#startdatetimepicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            defaultDate: $('#startdatetimepicker').val() !== "" ? moment($('#startdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : (start_date != '' ? moment(start_date, 'DD-MM-YYYY HH:mm') : moment()),
            //minDate: moment(),
            maxDate: !startDateFirstTime1 && $('#enddatetimepicker').val() !== "" ? moment($('#enddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : false,
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
            },
          })
        } catch(e){
          $('#startdatetimepickererror').removeClass('d-none')
          $('#startdatetimepicker').addClass('d-none')
        }

        $('#startdatetimepicker').on("show.datetimepicker", ({date, oldDate}) => {
          $('#enddatetimepicker').datetimepicker('hide')
        })

        $('#startdatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
          if(oldDate != null){
            if(startDateFirstTime1 && $('#enddatetimepicker').val() == "")
              $('#enddatetimepicker').val(moment(date).add(1, 'd').format('DD-MM-YYYY HH:mm'))
            startDateFirstTime1 = false
            endDateFirstTime1 = false
            $('#startdatetimepicker').datetimepicker('hide')
            
            init_start_date()
            init_end_date()
          }
        })
      }

      function init_end_date(){
        if(!endDateFirstTime){
          $('#enddatetimepicker').datetimepicker('destroy')
        }
        endDateFirstTime = false
        $('#enddatetimepickererror').addClass('d-none')
        $('#enddatetimepicker').removeClass('d-none')
        var end_date = '{{ !empty($event) ? $event->end_date->formatLocalized('%d-%m-%Y %H:%M') : '' }}'
        
        try{
          $('#enddatetimepicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            defaultDate: $('#enddatetimepicker').val() !== "" ? moment($('#enddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : (end_date != "" ? moment(end_date, 'DD-MM-YYYY HH:mm') : moment().add(1, 'd')),
            minDate: $('#startdatetimepicker').val() !== "" ? moment($('#startdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment().add(1, 'd'),
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
            },
          })
        } catch(e){
          console.log(e)
          $('#enddatetimepickererror').removeClass('d-none')
          $('#enddatetimepicker').addClass('d-none')
        }

        $('#enddatetimepicker').on("show.datetimepicker", ({date, oldDate}) => {
          $('#startdatetimepicker').datetimepicker('hide')
        })

        $('#enddatetimepicker').on("change.datetimepicker", ({date, oldDate}) => {
          if(oldDate != null){
            if(startDateFirstTime1 && $('#startdatetimepicker').val() == "")
              $('#startdatetimepicker').val(moment(date).subtract(1, 'd').format('DD-MM-YYYY HH:mm'))
            startDateFirstTime1 = false
            endDateFirstTime1 = false
            $('#enddatetimepicker').datetimepicker('hide')

            init_start_date()
            init_end_date()
          }
        })
      }

      $(document).ready(() => {
        @if(!empty($event))
          @foreach($event->image as $image)
            arr_url_image.push('{{ url("/image/event?file_name=".$image->file_name) }}')
            arr_file_name.push('{{ $image->file_name }}')
            arr_id.push('{{ $image->id }}')
            arr_image.push(null)
          @endforeach
          on_change_multiple_imageevent_image()
        @endif
        init_start_date()
        init_end_date()

        $('#phone').keyup(() => {
          $('#phone').val(phone_validation($('#phone').val()))
        })
        $('#submit').click((e) => {
          
        })
      })
    </script>
  @endpush
@endsection
