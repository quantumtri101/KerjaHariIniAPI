<div>
  <table class="table table-bordered">
    <thead>
      <tr>
        {{-- <th>{{ __('general.status_publish') }}</th> --}}
        <th>{{ __('general.name') }}</th>
        @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
          <th>{{ __('general.action') }}</th>
        @endif
      </tr>
    </thead>
    <tbody id="table_body_qualification">
      
    </tbody>
  </table>
</div>

@push('script')
  <script>
    var arr_qualification = []

    async function manage_arr_qualification(){
      var str = ""
      if(arr_qualification.length > 0){
        for(let x in arr_qualification){
          str += `
            <tr>
              <td>${arr_qualification[x].name}</td>
              @if((!empty($jobs) && $jobs->allow_edit) || empty($jobs))
                <td>
                  <button class="btn btn-primary" type="button" onclick="on_edit(${x})">{{ __("general.edit") }}</button>
                  <button class="btn btn-danger" type="button" onclick="on_delete(${x})">{{ __("general.delete") }}</button>
                </td>
              @endif
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

      $('#arr_qualification').val(JSON.stringify(arr_qualification))
      $('#table_body_qualification').html(str)
    }

    $(document).ready(() => {
      
    })
  </script>
@endpush

@push('afterScript')
@if(!empty($jobs))
  @foreach($jobs->qualification as $qualification)
    arr_qualification.push({
      id: '{{ $qualification->id }}',
      status_publish: '{{ __("general.".($qualification->is_publish == 1 ? 'publish' : 'not_publish')) }}',
      is_publish: '{{ $qualification->is_publish }}',
      name: '{{ $qualification->name }}',
    })
  @endforeach
@endif

manage_arr_qualification()
@endpush