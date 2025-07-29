<div class="">
  <input type="hidden" id="arr_experience" name="arr_experience"/>
  <div class="row">
    <div class="col-12 col-lg-4">
      @include('user.customer.oncall.component.action.experience.add')
    </div>
    <div class="col-12 col-lg-8 mt-3 mt-lg-0">
      @include('user.customer.oncall.component.action.experience.table')
    </div>
  </div>
</div>

@push('script')
  <script>
    function check_experience(){
      var message = ""
      if(arr_experience.length == 0)
        message = "{{ __('general.experience_empty') }}"
      return message
    }
  </script>
  @endpush