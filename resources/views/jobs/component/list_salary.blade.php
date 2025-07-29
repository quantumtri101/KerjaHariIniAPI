<div>
  @if($jobs->status == 'open')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editSalary" onclick="set_salary_data_modal()">
      {{ __('general.add') }}
    </button>
  @endif

  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_salary_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.name') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.salary') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>

  <div class="modal fade" id="editSalary" tabindex="-1" aria-labelledby="editSalaryLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ url('/jobs/salary') }}" id="formEditSalary" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="salary_id" name="id"/>
          <input type="hidden" id="jobs_id" name="jobs_id" value="{{ !empty($jobs_application) ? $jobs_application->jobs->id : $jobs->id }}"/>
          <input type="hidden" id="salary_is_approve" name="is_approve" value="1"/>
  
          <div class="modal-header">
            <h5 class="modal-title" id="editSalaryLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.type') }}</label>
              <select name="type" id="salary_type" class="form-control">
                <option value="">{{ __('general.choose_type') }}</option>
                <option value="main">{{ __('general.main_salary') }}</option>
                <option value="additional">{{ __('general.additional') }}</option>
              </select>
            </div>
  
            <div class="form-group d-none" id="additional_container">
              <label for="exampleInputEmail1">{{ __('general.name') }}</label>
              <input type="text" class="form-control" name="custom_name" id="salary_custom_name">
            </div>
  
            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.salary') }}</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp.</span>
                </div>
                <input type="text" class="form-control" name="salary" id="salary_salary" aria-describedby="emailHelp">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.close') }}</button>
            <button class="btn btn-primary">{{ __('general.submit') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var salary_datatable = null
  function set_salary_data_modal(data = null){
    if(data != null){
      data = JSON.parse(decodeURI(data))
    }

    $('#salary_id').val(data != null ? data.id : '')
    $('#salary_type').val(data != null ? data.type : '')
    $('#salary_custom_name').val(data != null ? data.custom_name : '')
    $('#salary_salary').val((data != null ? data.salary : 0).toLocaleString(locale_string))
    if($('#salary_type').val() == "additional")
      $('#additional_container').removeClass('d-none')
    else
      $('#additional_container').addClass('d-none')
    
    $('#editSalaryLabel').html(data != null ? '{{ __("general.edit_salary") }}' : '{{ __("general.add_salary") }}')
    $('#formEditSalary').attr('action', data != null ? '{{ url("/jobs/salary/edit") }}' : '{{ url("/jobs/salary") }}')
  }

  $(document).ready(function () {
    $('#salary_salary').keyup(() => {
      $('#salary_salary').val(to_currency_format($('#salary_salary').val()))
    })

    $('#salary_type').change(() => {
      if($('#salary_type').val() == "additional")
      $('#additional_container').removeClass('d-none')
    else
      $('#additional_container').addClass('d-none')
    })

    salary_datatable = $('#list_salary_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/salary?jobs_id='.$jobs->id) }}",
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
        {"data" : "name", name: "type"},
        {"data" : "salary_format", name: "salary"},
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
            @if($jobs->status == 'open')
              str += '<div style="">'
                if(row.type !== "main" && row.is_sent == 0){
                  str += `<button class="btn btn-primary" data-toggle="modal" data-target="#editSalary" onclick="set_salary_data_modal('${encodeURI(json)}')">{{ __('general.edit') }}</button>`
                  str += `<a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/api/jobs/salary/delete') }}?id=${row.id}')">Delete</a>`
                }
                if(row.is_approve == 0){
                  str += `
                    <form method="post" action="{{ url('/jobs/salary/change-approve') }}">
                      @csrf
                      <input type="hidden" name="id" value="${row.id}"/>
                      <input type="hidden" name="is_approve" value="1"/>
                      <button class="btn btn-primary">{{ __('general.set_approve') }}</button>
                    </form>
                  `
                }
              str += '</div>'
            @endif
            return str
          },
        },
      ]
    })
  })
</script>
@endpush