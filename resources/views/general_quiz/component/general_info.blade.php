<div class="row">
  <div class="col-12">
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.id') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $general_quiz_question->id }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.status_publish') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ __('general.'.($general_quiz_question->is_publish == 1 ? 'publish' : 'not_publish')) }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ __('general.question') }}</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $general_quiz_question->name }}" aria-describedby="emailHelp" disabled>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 d-flex mt-3">
    <a class="btn btn-primary" onclick="save_current_page('{{ __('general_quiz.detail') }}')" href="{{ url('/general-quiz/action?id='.$general_quiz_question->id) }}">{{ __('general.edit') }}</a>
  </div>
</div>