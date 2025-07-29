<div class="">
  <input type="hidden" id="arr_qualification" name="arr_qualification"/>
  <div class="row">
    @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
      <div class="col-12 col-lg-4">
        @include('jobs.component.action.qualification.add')
      </div>
    @endif
    <div class="col-12 {{ (!empty($jobs) && $jobs->allow_edit) || empty($jobs) ? 'col-lg-8' : '' }} mt-3 mt-lg-0">
      @include('jobs.component.action.qualification.table')
    </div>
  </div>
</div>

@push('script')
  <script>
    function check_qualification(){
      var message = ""
      if(arr_qualification.length == 0)
        message = "{{ __('general.qualification_empty') }}"
      return message
    }
  </script>
  @endpush