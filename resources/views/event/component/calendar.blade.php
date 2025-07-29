<div class="row">
  <div class="col-12">
    <div id="calendar"></div>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var jobs_datatable = null
  var calendar = null

  function reload_calendar(){
    if(calendar == null){
      var context = this
      calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        eventClick: (info) => {
          if(info.event.id != '{{ $event->id }}'){
            save_current_page('{{ __("event.detail") }}')
            location.href = '{{ url("/jobs/detail") }}?id=' + info.event.id
          }
        },
      })
      
      setTimeout(() => {
        
        calendar.render()

        calendar.addEvent({
          id: '{{ $event->id }}',
          title: '{{ $event->name }}',
          start: '{{ $event->start_date->formatLocalized("%Y-%m-%d") }}',
          end: '{{ $event->end_date->addDays(1)->formatLocalized("%Y-%m-%d") }}',
          color: '#44A244',
        })

        @foreach($event->jobs as $jobs)
          calendar.addEvent({
            id: '{{ $jobs->id }}',
            title: '{{ $jobs->name }}',
            start: '{{ $jobs->start_shift->start_date->formatLocalized("%Y-%m-%d") }}',
            end: '{{ $jobs->end_shift->end_date->addDays(1)->formatLocalized("%Y-%m-%d") }}',
            color: '#44A244',
          })
        @endforeach

        // console.log()
        calendar.gotoDate(moment('{{ $event->start_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD').toDate());
      }, 100);
    }
  }

  $(document).ready(function () {
    

    
  })
</script>
@endpush