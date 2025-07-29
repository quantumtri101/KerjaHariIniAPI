<div>
  {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editQualification" onclick="set_qualification_data_modal()">
    {{ __('general.add') }}
  </button> --}}

  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_qualification_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.qualification') }}</th>
          {{-- <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_publish') }}</th> --}}
          {{-- <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th> --}}
        </tr>
      </thead>
    </table>
  </div>

  <div class="modal fade" id="editQualification" tabindex="-1" aria-labelledby="editQualificationLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ url('/jobs/qualification') }}" id="formEditQualification" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="qualification_id" name="id"/>
          <input type="hidden" id="jobs_id" name="jobs_id" value="{{ $jobs->id }}"/>

          <div class="modal-header">
            <h5 class="modal-title" id="editQualificationLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>{{ __('general.status_publish') }}</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_publish" value="1" id="radio-qualification-publish">
                <label class="form-check-label" for="radio-qualification-publish">
                  {{ __('general.publish') }}
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_publish" value="0" id="radio-qualification-not_publish">
                <label class="form-check-label" for="radio-qualification-not_publish">
                  {{ __('general.not_publish') }}
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.qualification') }}</label>
              <input type="text" class="form-control" name="name" id="qualification_qualification" aria-describedby="emailHelp">
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
  var qualification_datatable = null
  function set_qualification_data_modal(data = null){
    if(data != null){
      data = JSON.parse(decodeURI(data))
    }

    $('#qualification_id').val(data != null ? data.id : '')
    $('#qualification_qualification').val(data != null ? data.name : '')
    if(data != null){
      $('#radio-qualification-publish').attr('checked', false)
      $('#radio-qualification-not_publish').attr('checked', false)

      $('#radio-qualification-' + data.status_publish_format).attr('checked', true)
    }
    
    $('#editQualificationLabel').html(data != null ? '{{ __("general.edit_qualification") }}' : '{{ __("general.add_qualification") }}')
    $('#formEditQualification').attr('action', data != null ? '{{ url("/jobs/qualification/edit") }}' : '{{ url("/jobs/qualification") }}')
  }

  $(document).ready(function () {
    qualification_datatable = $('#list_qualification_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/jobs/qualification?jobs_id='.$jobs->id) }}",
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
        {"data" : "name", name: "name"},
        // {"data" : "status_publish", name: "is_publish"},
        // {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        // {
        //   targets: -1,
        //   data: null,
        //   sorting: false,
        //   render: function(data, type, row, meta) {
        //     var json = JSON.stringify(row)
            
        //     var str = `
        //       <div style="width: 150px">
        //         <button class="btn btn-primary" data-toggle="modal" data-target="#editQualification" onclick="set_qualification_data_modal('${encodeURI(json)}')">{{ __('general.edit') }}</button>
        //         <a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/jobs/qualification/delete') }}?id=${row.id}')")>Delete</a>
        //       </div>
        //     `
        //     return str
        //   },
        // },
      ]
    })
  })
</script>
@endpush