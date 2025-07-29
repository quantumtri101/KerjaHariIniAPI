<div>
  <div class="form-group">
    <label>{{ __('general.skill') }}</label>
    <select id="skill_id" class="form-control"></select>
  </div>

  <div>
    <button class="btn btn-outline-dark" type="button" onclick="cancel_skill()">{{ __('general.cancel') }}</button>
    <button class="btn btn-primary" type="button" onclick="submit_skill()">{{ __('general.submit') }}</button>
  </div>
</div>

@push('script')
  <script>
    var index = -1
    var is_publish = 1
    var arr_base_skill = []
    function on_edit_skill(index1){
      var skill = arr_skill[index1]
      index = index1
      is_publish = skill.is_publish
      $('#skill_id').val(skill.skill.id)
    }

    function on_delete_skill(index){
      var skill = arr_skill[index]
      arr_skill.splice(index, 1)
      manage_arr_skill()
    }

    function reset_skill(){
      is_publish = 1
      $('#skill_id').val('')
    }

    function submit_skill(){
      if($('#skill_id').val() === "")
        notify_user('{{ __("general.skill_empty") }}')
      else{
        var selected_skill = {}
        for(let skill of arr_base_skill){
          if(skill.id === $('#skill_id').val())
            selected_skill = skill
        }

        var flag = false
        for(let skill of arr_skill){
          if(skill.skill.id === selected_skill.id){
            flag = true
            break
          }
        }

        if(!flag){
          
          var skill = {
            skill: selected_skill,
          }
          if(index < 0)
            arr_skill.push(skill)
          else
            arr_skill[index] = skill
            
          reset_skill()
          manage_arr_skill()
        }
        else
          reset_skill()
      }
    }

    function cancel_skill(){
      reset_skill()
    }
    
    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
$('#skill_id').select2({
  ajax: {
    url: '{{ url('/api/skill/all') }}',
    dataType: 'json',
    processResults: function (data) {
      arr_base_skill = data.data
      return {
        results: data.data
      };
    }
  },
})
manage_arr_skill()
@endpush