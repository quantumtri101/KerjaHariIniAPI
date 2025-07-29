<div>
  @if((Auth::user()->type->name == "RO" || Auth::user()->type->name == "admin") && !$already_requested_all)
    <button class="btn btn-primary" data-toggle="modal" data-target="#check_log_upload_all">Request All to Client</button>
  @elseif(Auth::user()->type->name != "RO" && Auth::user()->type->name != "admin")
    @if($allow_approve_check_log)
    <div>
      <form method="POST" class="d-inline-block" action="{{ url('/jobs/shift/approve') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $jobs_shift->id }}"/>
        <input type="hidden" name="approve_type" value="per_staff"/>
        <button class="btn btn-primary" {{ $jobs_shift->approve_type != "none" ? 'disabled' : '' }}>{{ __('general.approve_per_staff') }}</button>
      </form>
      <form method="POST" class="d-inline-block" action="{{ url('/jobs/shift/approve') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $jobs_shift->id }}"/>
        <input type="hidden" name="approve_type" value="all"/>
        <button class="btn btn-primary" {{ $jobs_shift->approve_type != "none" ? 'disabled' : '' }}>{{ __('general.approve_all') }}</button>
      </form>
    </div>
    @endif
  @endif
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_check_log_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.type') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.gender') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.id_no') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.check_in_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.check_out_date') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.decline_reason') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.approval') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('layout.modal.edit_check_log_date')
@include('layout.modal.check_log_request_approve')
@include('layout.modal.check_log_request_approve_all')
@include('layout.modal.decline_check_log')

@push('script')
<script type="text/javascript">
  var jobs_datatable = null

  function approvedAction(data){
    jobsShiftMinDate = moment('{{ $jobs_shift->start_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')
    jobsShiftMaxDate = moment('{{ $jobs_shift->end_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')
    $('#checkindatetimepicker').val(data.check_in2_at_format)
    $('#checkoutdatetimepicker').val(data.check_out2_at_format)
    $('#check_log_date_user_id').val(data.user.id)
    $('#edit_check_log_date').modal('show')

    init_check_in_date()
    init_check_out_date()
  }

  function requestApprovedAction(data){
    $('#check_log_upload_user_id').val(data.user_id)
    $('#check_log_upload').modal('show')
  }

  function requestApprovedAllAction(data){
    $('#check_log_upload_user_id').val(data.user_id)
    $('#check_log_upload').modal('show')
  }

  function declinedAction(data){
    $('#check_log_decline_user_id').val(data.user.id)
    $('#check_log_decline_jobs_shift_id').val('{{ $jobs_shift->id }}')
    $('#check_log_decline_modal').modal('show')
  }

  function downloadDocument(data){
    for(let x in data.document){
      setTimeout(() => {
        window.open("{{ url('/check-log/document/download') }}?id=" + data.document[x].id, "_blank")
      }, 100 * x);
    }
  }

  $(document).ready(function () {
    jobs_datatable = $('#list_check_log_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      dom: 'Bfrtip',
      buttons: [
        // {
        //   className: 'btn btn-primary',   
        //   text : "{{ __('general.add') }}",
        //   action: function ( e, dt, node, config ) {
        //     save_current_page("{{ __('check_log.title') }}")
        //     location.href = "{{ url('/check-log/action') }}"
        //   },
        //   init: function(api, node, config) {
        //     $(node).removeClass('dt-button')
        //   },
        // },
        {
          className: 'btn btn-primary',   
          text : "{{ __('general.export_excel') }}",
          action: function ( e, dt, node, config ) {
            location.href = "{{ url('/check-log/export/shift/excel').'?id='.$jobs_shift->id }}"
          },
          init: function(api, node, config) {
            $(node).removeClass('dt-button')
          },
        },
        {
          className: 'btn btn-primary',   
          text : "{{ __('general.export_pdf') }}",
          action: function ( e, dt, node, config ) {
            location.href = "{{ url('/check-log/export/shift/pdf').'?id='.$jobs_shift->id }}"
          },
          init: function(api, node, config) {
            $(node).removeClass('dt-button')
          },
        },
      ],
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/check-log') }}?api_type=check_in&jobs_shift_id={{ $jobs_shift->id }}",
        type : "GET",
        dataType : "json",
        headers : {
          "content-type": "application/json",
          "accept": "application/json",
          "X-CSRF-TOKEN": "{{csrf_token()}}"
        },
      },
      "order" : [[0, "asc"]],
      // deferLoading: 2,
      "columns" : [
        {"data" : "user_name", name: "user.name"},
        {"data" : "type_name", name: "type.name"},
        {"data" : "gender", name: "resume.gender"},
        {"data" : "id_no", name: "resume.id_no"},
        {"data" : "check_in_at_format", name: "date"},
        {"data" : "check_out_at_format", name: "date"},
        {"data" : "decline_reason", name: "decline_reason"},
        {"data" : "action", "orderable" : false},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -2,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var json = JSON.stringify(row)
            
            var str = ""
            str += '<div style="width: auto">'
              @if(Auth::user()->type->name == "RO" || Auth::user()->type->name == "admin")
                @if($jobs_shift->start_date < \Carbon\Carbon::now())
                  if(row.is_approve_check_log == "not_yet_requested" || row.is_approve_check_log == "declined")
                    str += `
                      <button class="btn btn-primary" onclick='approvedAction(${json})'>Edit</button>
                      <button class="btn btn-primary" onclick='requestApprovedAction(${json})'>Request to Client</button>
                    `
                  else
                    str += `<p class="m-0">${row.is_approve_check_log_format}</p>`
                @else
                  str += `<p class="m-0">${row.is_approve_check_log_format}</p>`
                @endif
              @else
                @if($allow_approve_check_log)
                  @if($jobs_shift->approve_type != "none")
                    if(row.is_approve_check_log == 'requested')
                      str += `
                        <form method="POST" action="{{ url('/check-log/approve') }}" class="d-inline-block">
                          @csrf
                          <input type="hidden" name="jobs_shift_id" value="{{ $jobs_shift->id }}"/>
                          <input type="hidden" name="user_id" id="user_id" value="${row.user_id}"/>
                          <input type="hidden" name="is_approve" value="approved"/>
                          <button class="btn btn-primary" >Approved</button>
                        </form>
                        <button class="btn btn-primary" onclick='declinedAction(${json})'>Decline</button>
                      `
                    else
                      str += `<p class="m-0">${row.is_approve_check_log_format}</p>`
                  @else
                    str += `<p class="m-0">${row.is_approve_check_log_format}</p>`
                  @endif
                @else
                  str += `<p class="m-0">${row.is_approve_check_log_format}</p>`
                @endif
              @endif
            str += '</div>'
            return str
          },
        },
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var json = JSON.stringify(row)
            
            var str = ""
            str += '<div style="width: auto">'
              if(row.latitude != null && row.longitude != null)
                str += `<a href="{{ url('/check-log/maps') }}?id=${row.id}" onclick="save_current_page('{{ __('check_log.detail') }}')" class="btn btn-primary mr-1" >View Maps</a>`
              if(row.document.length > 0)
                str += `<button class="btn btn-primary mr-1" onclick='downloadDocument(${json})'>{{ __('general.download_all_file') }}</button>`
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush