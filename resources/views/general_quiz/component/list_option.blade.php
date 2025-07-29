<div>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editOption" onclick="set_option_data_modal()">
    {{ __('general.add') }}
  </button>

  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_option_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.option') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_publish') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.status_true') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.action') }}</th>
        </tr>
      </thead>
    </table>
  </div>

  <div class="modal fade" id="editOption" tabindex="-1" aria-labelledby="editOptionLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ url('/general-quiz/option') }}" id="formEditOption" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="option_id" name="id"/>
          <input type="hidden" id="general_quiz_question_id" name="general_quiz_question_id" value="{{ $general_quiz_question->id }}"/>

          <div class="modal-header">
            <h5 class="modal-title" id="editOptionLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>{{ __('general.status_publish') }}</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_publish" value="1" id="radio-option-publish">
                <label class="form-check-label" for="radio-option-publish">
                  {{ __('general.publish') }}
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_publish" value="0" id="radio-option-not_publish">
                <label class="form-check-label" for="radio-option-not_publish">
                  {{ __('general.not_publish') }}
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">{{ __('general.option') }}</label>
              <input type="text" class="form-control" name="option" id="option_option" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label>{{ __('general.status_true') }}</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_true" value="1" id="radio-option-true">
                <label class="form-check-label" for="radio-option-true">
                  {{ __('general.true') }}
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_true" value="0" id="radio-option-false">
                <label class="form-check-label" for="radio-option-false">
                  {{ __('general.false') }}
                </label>
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
  var option_datatable = null
  function set_option_data_modal(data = null){
    if(data != null){
      data = JSON.parse(decodeURI(data))
    }

    $('#option_id').val(data != null ? data.id : '')
    $('#option_option').val(data != null ? data.option : '')
    if(data != null){
      $('#radio-option-publish').attr('checked', false)
      $('#radio-option-not_publish').attr('checked', false)
      $('#radio-option-true').attr('checked', false)
      $('#radio-option-false').attr('checked', false)

      $('#radio-option-' + data.status_publish_format).attr('checked', true)
      $('#radio-option-' + (data.is_true == 1 ? 'true' : 'false')).attr('checked', true)
    }
    
    $('#editOptionLabel').html(data != null ? '{{ __("general.edit_option") }}' : '{{ __("general.add_option") }}')
    $('#formEditOption').attr('action', data != null ? '{{ url("/general-quiz/option/edit") }}' : '{{ url("/general-quiz/option") }}')
  }

  $(document).ready(function () {
    option_datatable = $('#list_option_datatable').DataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{{ url('api/general-quiz/option?general_quiz_question_id='.$general_quiz_question->id) }}",
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
        {"data" : "option", name: "option"},
        {"data" : "status_publish", name: "is_publish"},
        {"data" : "status_true", name: "is_true"},
        {"data" : "action", "orderable" : false},
      ],
      "columnDefs" : [
        {
          targets: -1,
          data: null,
          sorting: false,
          render: function(data, type, row, meta) {
            var json = JSON.stringify(row)
            
            var str = `
              <div style="width: 150px">
                <button class="btn btn-primary" data-toggle="modal" data-target="#editOption" onclick="set_option_data_modal('${encodeURI(json)}')">{{ __('general.edit') }}</button>
                <a class="btn btn-danger ml-3" href="#!" onclick="alertDelete('{{ url('/general-quiz/option/delete') }}?id=${row.id}')")>Delete</a>
              </div>
            `
            return str
          },
        },
      ]
    })
  })
</script>
@endpush