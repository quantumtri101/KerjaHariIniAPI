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
    "title" => __($lang_file.'.choose_staff'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => __($lang_file.'.choose_staff'),
    'include_back_button' => false,
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" id="jobsForm" class="mt-3" action="{{ url('/jobs/choose-staff') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="jobs_id" value="{{ Request::get('id') }}"/>
        {{-- <input type="hidden" name="is_live_app" id="is_live_app"/>
        <input type="hidden" name="publish_date" id="publish_date"/> --}}

        <div class="mb-3">
          <h5 id="remaining_user">{{ __('general.remaining_user') }}: {{ count($jobs->application_online) }} / {{ __('general.num_person', ['num' => $jobs->num_people_required]) }}</h5>
        </div>

        <div id="wizard1">
          @foreach($arr_tab as $tab)
            <h3>{{ __('general.'.$tab["id"]) }}</h3>
            <section>
              @include($tab["component"])
            </section>
          @endforeach
        </div>
      </form>
    </div>
  </div>

  {{-- @include('layout.modal.notify_auto_live_choose_staff')
  @include('layout.modal.notify_not_live_choose_staff')
  @include('layout.modal.publish_date_choose_staff') --}}

  @push('script')
    <script>
      var arr_application = []
      function on_tab_clicked(id){
        localStorage.setItem('menu', id)
      }
      
      function remaining_user(){
        var total_user_applied = arr_user_regular.length + arr_user_casual.length + arr_user_casual_all.length
        var num_people_required = {{ $jobs->num_people_required }}
        $('#remaining_user').html(`
          {{ __('general.remaining_user') }}: ${total_user_applied} / ${num_people_required} {{ __('general.person') }}
        `)

        if(num_people_required > total_user_applied){
          $(`.btn-regular-set-applied`).attr('disabled', false)
          $(`.btn-casual-set-applied`).attr('disabled', false)
          $(`.btn-casual-all-set-applied`).attr('disabled', false)
        }
        else{
          $(`.btn-regular-set-applied`).attr('disabled', true)
          $(`.btn-casual-set-applied`).attr('disabled', true)
          $(`.btn-casual-all-set-applied`).attr('disabled', true)
        }
      }
      
      $(document).ready(async() => {
        var menu = await get_menu_detail()
          
        localStorage.setItem('menu', menu)
          
        $('#' + menu + '-tab').addClass('active')
        $('#' + menu).addClass('show active')

        
        @foreach($jobs->application_online as $application)
          arr_application.push({
            id: '{{ $application->id }}',
            user_id: '{{ $application->user_id }}',
          })
          @if($application->user_type == "regular")
            arr_user_regular.push({
              id: '{{ $application->user->id }}',
              jobs_application_id: '{{ $application->id }}',
            })
            manage_arr_user_regular('{{ $application->user->id }}')
          @elseif($application->user_type == "casual")
            arr_user_casual.push({
              id: '{{ $application->user->id }}',
              jobs_application_id: '{{ $application->id }}',
            })
            manage_arr_user_casual('{{ $application->user->id }}')
          @else
            arr_user_casual_all.push({
              id: '{{ $application->user->id }}',
              jobs_application_id: '{{ $application->id }}',
            })
            manage_arr_user_casual_all('{{ $application->user->id }}')
          @endif
        @endforeach


        var wizard = $('#wizard1').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          // autoFocus: true,
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanged: function (event, currentIndex, priorIndex) {
            if(currentIndex == 0)
              $('#btn-cancel').removeClass('d-none')
            else
              $('#btn-cancel').addClass('d-none')
          },
          onFinished: function (event, currentIndex) {
            var menu1 = -1
            var message = ""
            var message_user_casual = check_user_casual()
            var message_user_regular = check_user_regular()
            var message_user_casual_all = check_user_casual_all()

            if(message_user_casual != ""){
              message = message_user_casual
              menu1 = 0
            }
            else if(message_user_regular != ""){
              message = message_user_regular
              menu1 = 1
            }
            if(message_user_casual_all != ""){
              message = message_user_casual_all
              menu1 = 2
            }

            if(message !== ""){
              $("#wizard1-t-"+menu1).get(0).click();
              // $('#' + menu1 + '-tab').tab('show')
              notify_user(message, event)
              return
            }
            back_page(false)
            
            var total_user_applied = arr_user_regular.length + arr_user_casual.length + arr_user_casual_all.length
            var num_people_required = {{ $jobs->num_people_required }}

            $('#jobsForm').trigger('submit')
            // if(total_user_applied < num_people_required)
            //   $('#notify_auto_live_choose_staff').modal('show')
            // else
            //   $('#notify_not_live_choose_staff').modal('show')
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
