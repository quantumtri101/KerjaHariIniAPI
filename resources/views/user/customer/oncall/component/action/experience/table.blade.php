<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        {{-- <th>{{ __('general.status_publish') }}</th> --}}
        <th>{{ __('general.name') }}</th>
        <th>{{ __('general.working_year') }}</th>
        <th>{{ __('general.company') }}</th>
        <th>{{ __('general.location') }}</th>
        <th>{{ __('general.description') }}</th>
        <th>{{ __('general.action') }}</th>
      </tr>
    </thead>
    <tbody id="table_body_experience">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_experience = []

    async function manage_arr_experience(){
      var str = ""
      if(arr_experience.length > 0){
        for(let x in arr_experience){
          str += `
            <tr>
              <td>${arr_experience[x].name}</td>
              <td>${arr_experience[x].start_year} - ${arr_experience[x].end_year}</td>
              <td>${arr_experience[x].company}</td>
              <td>${arr_experience[x].location}</td>
              <td>${arr_experience[x].description}</td>
              <td>
                <button class="btn btn-primary" type="button" onclick="on_edit_experience(${x})">{{ __("general.edit") }}</button>
                <button class="btn btn-danger" type="button" onclick="on_delete_experience(${x})">{{ __("general.delete") }}</button>
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

      $('#arr_experience').val(JSON.stringify(arr_experience))
      $('#table_body_experience').html(str)
    }

    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
@if(!empty($user) && count($user->resume) > 0)
  @foreach($user->resume[0]->experience as $experience)
    arr_experience.push({
      id: '{{ $experience->id }}',
      name: '{{ $experience->name }}',
      start_year: '{{ $experience->start_year }}',
      end_year: '{{ $experience->end_year }}',
      company: '{{ $experience->corporation }}',
      location: '{{ $experience->city->name }}',
      city: {
        id: '{{ $experience->city->id }}',
      },
      description: '{{ $experience->description }}',
    })
  @endforeach
@endif

manage_arr_experience()
@endpush