<div>
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
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.approval') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('layout.modal.edit_check_log_date')

@push('script')
<script type="text/javascript">
  var jobs_datatable = null

  function approvedAction(data){
    // jobsShiftMinDate = moment('{{ $jobs_shift->start_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')
    // jobsShiftMaxDate = moment('{{ $jobs_shift->end_date->formatLocalized("%d-%m-%Y %H:%M") }}', 'DD-MM-YYYY HH:mm')
    $('#checkindatetimepicker').val(data.check_in2_at_format)
    $('#checkoutdatetimepicker').val(data.check_out2_at_format)
    $('#user_id').val(data.user.id)
    $('#edit_check_log_date').modal('show')

    init_check_in_date()
    init_check_out_date()
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
      ],
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/check-log').'?api_type=check_in&jobs_shift_id='.$jobs_shift->id }}",
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
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var json = JSON.stringify(row)
            
            var str = ""
            str += '<div style="width: auto">'
              @if($jobs_shift->approve_type != "none")
                if(row.is_approve_check_log == 0)
                  str += `
                    <button class="btn btn-primary" onclick='approvedAction(${json})'>Approved</button>
                    <button class="btn btn-primary" >Decline</button>
                  `
                else
                  str += `<p class="m-0">${row.is_approve_check_log == 1 ? 'Approved' : 'Declined'}</p>`
              @endif
            str += '</div>'
            return str
          },
        },
      ]
    })
  })
</script>
@endpush