<div class="">
  <input type="hidden" id="arr_skill" name="arr_skill"/>
  <div class="row">
    <div class="col-12 col-lg-4">
      @include('user.customer.oncall.component.action.skill.add')
    </div>
    <div class="col-12 col-lg-8 mt-3 mt-lg-0">
      @include('user.customer.oncall.component.action.skill.table')
    </div>
  </div>
</div>

@push('script')
  <script>
    function check_skill(){
      var message = ""
      if(arr_skill.length == 0)
        message = "{{ __('general.skill_empty') }}"
      return message
    }
  </script>
  @endpush