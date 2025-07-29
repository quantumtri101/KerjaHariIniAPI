@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => Request::has('id') ? [
      __('general.customer_oncall'),
      __('customer_oncall.detail'),
      __('customer_oncall.edit'),
    ] : [
      __('general.customer_oncall'),
      __('customer_oncall.add'),
    ],
    "title" => Request::has('id') ? __('customer_oncall.edit') : __('customer_oncall.add'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => Request::has('id') ? __('customer_oncall.edit') : __('customer_oncall.add'),
  ])

  <div class="card mt-3">
    <div class="card-body position-relative">
      <form method="post" class="mt-3" id="jobsForm" action="{{ url(Request::has('id') ? '/user/customer/oncall/edit' : '/user/customer/oncall') }}" enctype="multipart/form-data">
        @csrf
        @if(Request::has('id'))
          <input type="hidden" name="id" value="{{ Request::get('id') }}"/>
        @endif

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

  @push('script')
    <script>
      var from_system = false
      // function init_birth_date(){
      //   $('#birth_date').datetimepicker('destroy')
      //   $('#birth_date').datetimepicker({
      //     format: 'DD/MM/YYYY',
      //     useCurrent: false,
      //     defaultDate: moment(birth_date, 'DD/MM/YYYY'),
      //     maxDate: moment().subtract(13, 'y'),
      //   })

      //   $('#birth_date').on("change.datetimepicker", ({date, oldDate}) => {
      //     if(oldDate != null){
      //       birth_date = $('#birth_date').val()
      //     }
      //   })
      // }

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
            else if(priorIndex == 1 && check_resume() != '')
              message = check_resume()
            else if(priorIndex == 2 && check_experience() != '')
              message = check_experience()
            else if(priorIndex == 3 && check_skill() != '')
              message = check_skill()
            else{
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
            var message_resume = check_resume()
            var message_experience = check_experience()
            var message_skill = check_skill()

            if(message_general != ""){
              message = message_general
              menu1 = 0
            }
            else if(message_resume != ""){
              message = message_resume
              menu1 = 1
            }
            else if(message_experience != ""){
              message = message_experience
              menu1 = 2
            }
            else if(message_skill != ""){
              message = message_skill
              menu1 = 3
            }

            if(message !== ""){
              $("#wizard1-t-"+menu1).get(0).click();
              // $('#' + menu1 + '-tab').tab('show')
              notify_user(message, event)
              return
            }
            back_page(false)
            $('#jobsForm').trigger('submit')
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
