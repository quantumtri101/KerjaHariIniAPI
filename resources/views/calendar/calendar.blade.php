@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.calendar'),
    ],
    "title" => __('calendar.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('calendar.title') }}</h5>

    {{-- <a class="btn btn-primary" href="{{ url('/event/action') }}"  onclick="save_current_page('{{ __('event.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="row">
    <div class="col-12">
      <div id="calendar"></div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
  var jobs_datatable = null
  var calendar = null
  var arr_event = []
  var arr_jobs = []

  function reload_calendar(){
    if(calendar == null){
      var context = this
      calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        eventClick: (info) => {
          var selected_data = {}
          for(let event of arr_event){
            if(info.event._def.publicId == event.id){
              selected_data = event
              break
            }
          }
          if(selected_data.id == null){
            for(let jobs of arr_jobs){
              if(info.event._def.publicId == jobs.id){
                selected_data = jobs
                break
              }
            }
          }

          if(selected_data.type == 'event'){
            save_current_page('{{ __("event.detail") }}')
            location.href = '{{ url("/event/detail") }}?id=' + selected_data.id
          }
          else if(selected_data.type == 'jobs'){
            save_current_page('{{ __("jobs.detail") }}')
            location.href = '{{ url("/jobs/detail") }}?id=' + selected_data.id
          }
        },
      })

      @foreach($arr_event as $event)
        arr_event.push({
          id: '{{ $event->id }}',
          name: '{{ $event->name }}',
          start_date: moment('{{ $event->start_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD'),
          end_date: moment('{{ $event->end_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD'),
          type: 'event',
        })

        @foreach($event->jobs as $jobs)
          @if(!empty($jobs->start_shift) && !empty($jobs->end_shift))
            arr_jobs.push({
              id: '{{ $jobs->id }}',
              name: '{{ $jobs->name }}',
              start_date: moment('{{ $jobs->start_shift->start_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD'),
              end_date: moment('{{ $jobs->end_shift->end_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD'),
              type: 'jobs',
            })
          @endif
        @endforeach
      @endforeach

      setTimeout(() => {
        calendar.render()

        @foreach($arr_event as $event)
          calendar.addEvent({
            id: '{{ $event->id }}',
            title: '{{ $event->name }}',
            start: '{{ $event->start_date->formatLocalized("%Y-%m-%d") }}',
            end: '{{ $event->end_date->addDays(1)->formatLocalized("%Y-%m-%d") }}',
            color: '#44A244',
            type: 'event',
          })
          

          @foreach($event->jobs as $jobs)
            @if(!empty($jobs->start_shift) && !empty($jobs->end_shift))
              calendar.addEvent({
                id: '{{ $jobs->id }}',
                title: '{{ $jobs->name }}',
                start: '{{ $jobs->start_shift->start_date->formatLocalized("%Y-%m-%d") }}',
                end: '{{ $jobs->end_shift->end_date->addDays(1)->formatLocalized("%Y-%m-%d") }}',
                color: '#FF0000',
                type: 'job',
              })
            @endif
          @endforeach

          // console.log()
          // calendar.gotoDate(moment('{{ $event->start_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD').toDate());
        @endforeach
      }, 100);
    }
  }

  $(document).ready(function () {
    reload_calendar()    
  })
</script>
@endpush