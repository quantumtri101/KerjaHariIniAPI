@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.jobs'),
      __($lang_file.'.detail'),
      __($lang_file.'.edit'),
    ] : [
      __('general.jobs'),
      __($lang_file.'.add'),
    ],
    "title" => Request::has('id') ? __($lang_file.'.edit') : __($lang_file.'.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __($lang_file.'.edit') : __($lang_file.'.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" id="jobsForm" class="mt-3" action="{{ url(Request::has('id') ? '/jobs/edit' : '/jobs') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif
        <input type="hidden" name="type" id="jobs_type"/>
        <input type="hidden" name="publish_start_date" id="publish_start_date"/>
        <input type="hidden" name="publish_end_date" id="publish_end_date"/>

        <div id="wizard1">
          @foreach($arr_tab as $tab)
            <h3>{{ __('general.'.$tab["id"]) }}</h3>
            <section>
              @include($tab["component"])
            </section>
          @endforeach
        </div>

        {{-- <ul class="nav nav-pills" id="detailTab" role="tablist">
          @foreach($arr_tab as $tab)
            <li class="nav-item" role="presentation">
              <button class="nav-link border-0" id="{{ $tab["id"] }}-tab" data-toggle="tab" data-target="#{{ $tab["id"] }}" type="button" role="tab" onclick="on_tab_clicked('{{ $tab["id"] }}')" aria-controls="{{ $tab["id"] }}" aria-selected="true">{{ __('general.'.$tab["id"]) }}</button>
            </li>
          @endforeach
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content mt-3" id="pills-detailTabContent">
          @foreach($arr_tab as $tab)
          <div class="tab-pane" id="{{ $tab["id"] }}" role="tabpanel" aria-labelledby="{{ $tab["id"] }}-tab">
            <div class="card">
              <div class="card-body">
                @include($tab["component"])
              </div>
            </div>
          </div>
          @endforeach
        </div> --}}

        {{-- <div class="form-group mt-3" >
          <a class="btn btn-outline-dark" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>
          <button class="btn btn-primary" id="submit">{{ __('general.submit') }}</button>
        </div> --}}
      </form>
    </div>
  </div>

  @include('layout.modal.jobs_submit_ask')
  @include('layout.modal.publish_date_add_jobs')

  @push('script')
    <script>
      var selected_event = {}
      var selected_menu = 0
      var from_system = false
      function on_tab_clicked(id){
        localStorage.setItem('menu', id)
      }
      
      $(document).ready(async() => {
        var menu = await get_menu_detail()
        localStorage.setItem('menu', menu)

        $('#' + menu + '-tab').addClass('active')
        $('#' + menu).addClass('show active')

        var wizard = $('#wizard1').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          // autoFocus: true,
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanged: function (event, currentIndex, priorIndex) {
            if(from_system){
              from_system = false
              return
            }
            var message = ""
            
            if(priorIndex == 0 && check_general() != '')
              message = check_general()
            else if(priorIndex == 1 && check_jobs() != '')
              message = check_jobs()
            else if(priorIndex == 2 && check_criteria() != '')
              message = check_criteria()
            else if(priorIndex == 3 && check_qualification() != '')
              message = check_qualification()
            else if(priorIndex == 4 && check_brief_interview() != '')
              message = check_brief_interview()
            else{
              if(priorIndex == 0 && currentIndex == 1){
                @foreach($arr_event as $event)
                  if($('#event_id').val() == '{{ $event->id }}')
                    selected_event = {
                      id: '{{ $event->id }}',
                      name: '{{ $event->name }}',
                      start_date: moment('{{ $event->start_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD'),
                      end_date: moment('{{ $event->end_date->formatLocalized("%Y-%m-%d") }}', 'YYYY-MM-DD'),
                    }
                @endforeach
  
                init_date()
              }
              if(currentIndex == 0)
                $('#btn-cancel').removeClass('d-none')
              else
                $('#btn-cancel').addClass('d-none')
            }
            
            if(message != ''){
              notify_user(message)
              window.setTimeout(() => {
                from_system = true
                $("#wizard1-t-"+priorIndex).get(0).click();
              }, 100)
            }
          },
          onFinished: function (event, currentIndex) {
            var menu1 = -1
            var message = ""
            var message_general = check_general()
            var message_jobs = check_jobs()
            var message_criteria = check_criteria()
            var message_qualification = check_qualification()
            var message_brief_interview = check_brief_interview()

            if(message_general != ""){
              message = message_general
              menu1 = 0
            }
            else if(message_jobs != ""){
              message = message_jobs
              menu1 = 1
            }
            else if(message_criteria != ""){
              message = message_criteria
              menu1 = 2
            }
            else if(message_qualification != ""){
              message = message_qualification
              menu1 = 3
            }
            else if(message_brief_interview != ""){
              message = message_brief_interview
              menu1 = 4
            }
            selected_menu = menu1

            if(message !== ""){
              $("#wizard1-t-"+menu1).get(0).click();
              // $('#' + menu1 + '-tab').tab('show')
              notify_user(message, event)
              return
            }

            @if(Request::has('id'))
              back_page(false)
              $('#jobsForm').trigger('submit')
            @else
              if($('#radio-open').is(':checked')){
                shift_start_date = $('#radio-split-no').is(':checked') ? moment($('#startdatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment($('#starttimepicker1').val(), 'DD-MM-YYYY HH:mm')
                shift_end_date = $('#radio-split-no').is(':checked') ? moment($('#enddatetimepicker').val(), 'DD-MM-YYYY HH:mm') : moment($('#endtimepicker1').val(), 'DD-MM-YYYY HH:mm')

                jobs_submit_ask_modal.shift_start_date = shift_start_date
                jobs_submit_ask_modal.shift_end_date = shift_end_date
                $('#jobs_submit_ask_modal').modal('show')
              }
              else{
                back_page(false)
                $('#jobsForm').trigger('submit')
              }
            @endif
          }
        });
        var $input = $('<a class="btn btn-outline-secondary" type="button" onclick="back_page()">{{ __('general.cancel') }}</a>');
        $input.prependTo($('.actions'));
        $('.actions').addClass('d-flex')
        $('ul[aria-label="Pagination"]').addClass('w-100 ml-3')

        @stack('afterScript')
      })
    </script>
  @endpush
@endsection
