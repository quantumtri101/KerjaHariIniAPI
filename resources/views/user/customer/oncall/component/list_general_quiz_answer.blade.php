<div>
  <div class="mt-3 table-responsive">
    <table class="table w-100" id="list_general_quiz_answer_datatable">
      <thead>
        <tr>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.question') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.option_true') }}</th>
          <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">{{ __('general.answer') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
<script type="text/javascript">
  $(document).ready(function () {

    datatable = $('#list_general_quiz_answer_datatable').dataTable({
      "processing" : true,
      "serverSide" : true,
      bLengthChange: false,
      responsive: true,
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      "ajax" : {
        url : "{!! url('api/general-quiz/answer?user_id='.$customer_oncall->id) !!}",
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
      "columns" : [
        {"data" : "id", "orderable" : false, },
        {"data" : "question", name: "general_quiz_question.name"},
        {"data" : "option_true", name: "general_quiz_option.option"},
        {"data" : "answer", name: "general_quiz_answer.general_quiz_option_id"},
      ],
    })
  })
</script>
@endpush