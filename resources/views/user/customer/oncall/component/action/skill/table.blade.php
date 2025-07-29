<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        {{-- <th>{{ __('general.status_publish') }}</th> --}}
        <th>{{ __('general.name') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_skill">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_skill = []

    async function manage_arr_skill(){
      var str = ""
      if(arr_skill.length > 0){
        for(let x in arr_skill){
          str += `
            <tr>
              <td>${arr_skill[x].skill.name}</td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit_skill(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete_skill(${x})">{{ __("general.delete") }}</button>
              </td>
            </tr>
          `
        }
      }
      else
        str += `
          <tr>
            <td colspan="5" class="text-center">{{ __('general.no_data') }}</td>
          </tr>
        `

      $('#arr_skill').val(JSON.stringify(arr_skill))
      $('#table_body_skill').html(str)
    }

    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
@if(!empty($user) && count($user->resume) > 0)
  @foreach($user->resume[0]->skill as $skill)
    arr_skill.push({
      id: '{{ $skill->id }}',
      skill: {
        id: '{{ $skill->skill->id }}',
        name: '{{ $skill->skill->name }}',
      }
    })
  @endforeach
@endif

manage_arr_skill()
@endpush