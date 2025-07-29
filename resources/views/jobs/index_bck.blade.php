@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [
      __('general.jobs'),
    ],
    "title" => __('jobs.title'),
  ])
@endsection

@section('content')
<div>
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="h3 mb-0 text-gray-800 font-weight-bold mb-3">{{ __('jobs.title') }}</h5>

    

    {{-- <a class="btn btn-primary" href="{{ url('/jobs/action') }}"  onclick="save_current_page('{{ __('jobs.title') }}')">{{ __('general.add') }}</a> --}}
  </div>

  <div class="mt-3 table-responsive d-none d-lg-block">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.id') }}</th>
          @if(Auth::user()->type->name == "admin")
            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.company_name') }}</th>
          @endif
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.event_name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_approve') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.num_staff') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_on_app') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_work') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="d-block d-lg-none">
    <a class="btn btn-primary" href="{{ url('/jobs/action') }}"  onclick="save_current_page('{{ __('jobs.title') }}')">{{ __('general.add') }}</a>

    <div class="list-group mt-3">
      @foreach($arr as $data)
        <div class="list-group-item d-block pd-y-20 rounded-top-0">
          <div class="d-flex justify-content-between align-items-center tx-12 mg-b-10">
            <a href="" class="tx-info">{{ $data->sub_category->name }}</a>
            <span>{{ $data->created_at->formatLocalized('%d %B %Y') }}</span>
          </div><!-- d-flex -->
          <h6 class="lh-3 mg-b-10"><a href="" class="tx-inverse hover-primary">{{ $data->name }}</a></h6>
          <div>
            <a class="btn btn-primary" onclick="save_current_page('{{ __('jobs.title') }}')" href="{{ url('/jobs/detail') }}?id={{ $data->id }}")>{{ __('general.detail') }}</a>
            @if((Auth::user()->type->name == "admin" || Auth::user()->type->name == "RO") && $data->status == 'open')
              <a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/jobs/delete') }}?id={{ $data->id }}')">Delete</a>
            @endif
            @if($data->status_approve == "not_yet_approved")
              <div class="mt-3">
                <form method="post" action="{{ url('/jobs/approve/change-approve') }}" class="d-inline-block">
                  @csrf
                  <input type="hidden" name="jobs_id" value="{{ $data->id }}"/>
                  <input type="hidden" name="status_approve" value="approved"/>
                  <button class="btn btn-primary">Approve</button>
                </form>

                <a class="btn btn-danger ml-3" href="#!" onclick="showDeclineModal('{{ $data->id }}')">Decline</a>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

@include('layout.modal.jobs_decline')
@include('layout.modal.publish_date_choose_staff', [
  "type" => "index",
])
@endsection

@push('script')
<script type="text/javascript">
  function showDeclineModal(jobs_id){
    $('#jobs_decline_jobs_id').val(jobs_id)
    $('#jobs_decline_modal').modal('show')
  }

  function showPublishDateModal(data){
    $('#publish_date_choose_staff').modal('show')

    shift_start_date = moment(data.shift[0].start_date, "YYYY-MM-DD HH:mm:ss")
    publish_date_jobs_id = data.id
    publish_start_date()
    publish_end_date()
    
  }

  function addModal(){
    save_current_page("{{ __('jobs.title') }}")
    location.href = "{{ url('/jobs/action') }}"
  }

  $(document).ready(async function () {
    var arr_button = []
    var arr_column = []
    var filter = ""
    @if(Auth::user()->type->name == "admin" || Auth::user()->type->name == "RO")
      arr_button.push(
        {
          className: 'btn btn-primary',   
          text : "{{ __('general.add') }}",
          action: function ( e, dt, node, config ) {
            save_current_page("{{ __('jobs.title') }}")
            location.href = "{{ url('/jobs/action') }}"
          },
          init: function(api, node, config) {
            $(node).removeClass('dt-button')
          },
        }
      )
    @endif

    @if(Auth::user()->type->name == "RO" || Auth::user()->type->name == "staff")
      arr_column.push(
        {"data" : "id", name: "id"},
        {"data" : "event_name", name: "event.name"},
        {"data" : "name", name: "name"},
        {"data" : "status_approve", name: "is_approve"},
        {"data" : "num_staff", name: "is_approve"},
        {"data" : "status_on_app", name: "is_live_app"},
        {"data" : "status_work", name: "is_approve"},
        {"data" : "action", "orderable" : false},
      )
    @else
      arr_column.push(
        {"data" : "id", name: "id"},
        {"data" : "company_name", name: "company.name"},
        {"data" : "event_name", name: "event.name"},
        {"data" : "name", name: "name"},
        {"data" : "status_approve", name: "is_approve"},
        {"data" : "num_staff", name: "is_approve"},
        {"data" : "status_on_app", name: "is_live_app"},
        {"data" : "status_work", name: "is_approve"},
        {"data" : "action", "orderable" : false},
      )
    @endif

    

    var datatable = $('#datatable').dataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      dom: '<"toolbar">frtip',
      buttons: arr_button,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs') }}?filter=" + filter,
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : [[0, "desc"]],
      // deferLoading: 2,
      "columns" : arr_column,
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            console.log(row)
            var start_date = moment(row.shift[0].start_date, "YYYY-MM-DD HH:mm:ss")

            var str = ""
            str += '<div style="">'
              str += `<a class="btn btn-primary mb-1" onclick="save_current_page('{{ __('jobs.title') }}')" href="{{ url('/jobs/detail') }}?id=${row.id}")>{{ __('general.detail') }}</a>`

              @if(Auth::user()->type->name == "admin" || Auth::user()->type->name == "RO")
                if(row.status == "open")
                  str += `<a class="btn btn-danger ml-1 mb-1" href="#!" onclick="alertDelete('{{ url('/jobs/delete') }}?id=${row.id}')">Delete</a>`
                if((row.is_approve == 0 || row.is_approve == 1) && row.staff_type == "closed" && row.num_people_required > row.application_online.length)
                  str += `<a class="btn btn-primary ml-1 mb-1" onclick="save_current_page('{{ __('jobs.title') }}')" href="{{ url('/jobs/choose-staff') }}?id=${row.id}")>{{ __('general.choose_user') }}</a>`
              @endif

              if(row.status_approve == "not_yet_approved")
                str += `
                  <div class="mt-3 mb-1">
                    <form method="post" action="{{ url('/jobs/approve/change-approve') }}" class="d-inline-block">
                      @csrf
                      <input type="hidden" name="jobs_id" value="${row.id}"/>
                      <input type="hidden" name="status_approve" value="approved"/>
                      <button class="btn btn-primary">Approve</button>
                    </form>

                    <a class="btn btn-danger ml-1" href="#!" onclick="showDeclineModal('${row.id}')">Decline</a>
                  </div>
                `

              @if(Auth::user()->type->name == "admin" || Auth::user()->type->name == "RO")
                if(row.is_approve == 1 && start_date.isAfter(moment())){
                  if(row.is_live_app == 1)
                    str += `
                      <form method="post" action="{{ url('/jobs/change-live') }}" class="d-inline-block ml-1">
                        @csrf
                        <input type="hidden" name="jobs_id" value="${row.id}"/>
                        <input type="hidden" name="is_live_app" value="0"/>
                        <button class="btn btn-primary">{{ __('general.change_not_live') }}</button>
                      </form>
                    `
                  else
                    str += `
                      <form method="post" class="jobsForm" key="${row.id}" action="{{ url('/jobs/change-live') }}" class="d-inline-block ml-1">
                        @csrf
                        <input type="hidden" name="jobs_id" value="${row.id}"/>
                        <input type="hidden" name="publish_start_date" class="publish_start_date" key="${row.id}"/>
                        <input type="hidden" name="publish_end_date" class="publish_end_date" key="${row.id}"/>
                      </form>
                      <button type="button" class="btn btn-primary" onclick='showPublishDateModal(${JSON.stringify(row)})'>{{ __('general.change_live') }}</button>
                    `
                }
              @endif
            str += '</div>'
            return str
          },
        },
      ]
    })

    $('.toolbar').addClass('d-inline-block')
    $('.toolbar').html(`
      <div class="d-flex align-items-center">
        <a class="btn btn-primary" href="#" onclick="addModal()">{{ __('general.add') }}</a>
        <div class="form-group ml-3 mb-0 d-flex align-items-center">
          <select id="filter" class="form-control">
            <option value="">{{ __('general.no_filter') }}</option>
            @foreach($arr_filter as $filter)
              <option value="{{ $filter['id'] }}">{{ __('general.'.$filter['id']) }}</option>
            @endforeach
          </select>
        </div>
      </div>
    `)

    $('#filter').change(function() {
      filter = $('#filter').val()
      datatable.api().ajax.url("{{ url('api/jobs') }}?filter=" + filter)
      datatable.api().ajax.reload()
      // console.log(datatable.api().ajax.url())
    })
  })

  
</script>
@endpush
