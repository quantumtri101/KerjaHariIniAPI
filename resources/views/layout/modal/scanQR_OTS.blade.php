<div class="modal fade" id="scan_qr_ots_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.scan_qr_ots') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/jobs/ots') }}">
          @csrf
          <input type="hidden" name="jobs_id" id="scan_qr_ots_jobs_id" value="{{ !empty($jobs) ? $jobs->id : '' }}" />

          <div class="form-group">
            <label for="exampleInputEmail1">{{ __('general.qr_content') }}</label>
            <input type="text" name="qr_content" id="qr_content" class="form-control">
          </div>

          <div id="resume_data"></div>

          <div class="form-group">
            <button class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    async function get_user(){
      var response = await request('{{ url("/api/user") }}?with_resume_html=true&id=' + $('#qr_content').val())
      if(response != null){
        if(response.status === "success"){
          var str = ''
          str += `
            <div class="mt-3 card">
              <div class="card-body">
                <div class="row bg-white">
                  <div class="col-6">
                    <div class="card">
                      <div class="card-body">
                        <img src="{{ url('/image/user') }}?file_name=${response.data.file_name}" style="width: 30rem; border-radius: 30rem;"/>
                        <p class="m-0">${response.data.name}</p>
                        <p class="m-0">${response.data.phone}</p>
                        <p class="m-0">{{ __('general.user_from') }}${response.data.resume.length > 0 ? response.data.resume[0].created_at_format : '-'}</p>
                      </div>
                    </div>

                    <div class="mt-3">
                      <div class="row">
                        <div class="col-12">
                          <p class="m-0">{{ __('general.personal_detail') }}</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.gender') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 ? response.data.resume[0].gender_format : '-'}</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.birth_date') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 ? response.data.resume[0].birth_date_format : '-'}</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.address') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 ? response.data.resume[0].address : '-'}</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.status') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 ? response.data.resume[0].marital_status_format : '-'}</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.last_education') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 && response.data.resume[0].education != null ? response.data.resume[0].education.name : '-'}</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.height') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 ? response.data.resume[0].height : '-'} cm</p>
                        </div>
                        <div class="col-6">
                          <p class="m-0">{{ __('general.weight') }}</p>
                        </div>
                        <div class="col-6 text-right">
                          <p class="m-0">${response.data.resume.length > 0 ? response.data.resume[0].weight : '-'} kg</p>
                        </div>
                      </div>
                    </div>

                    <div class="mt-3">
                      <div class="row">
                        <div class="col-12">
                          <p class="m-0">{{ __('general.skill') }}</p>
                        </div>
                        <div class="col-12">`
                          if(response.data.resume.length > 0){
                            for(let x in response.data.resume[0].skill)
                              str += `<p class="m-0">${parseFloat(x) + 1}. ${response.data.resume[0].skill[x].skill != null ? response.data.resume[0].skill[x].skill.name : response.data.resume[0].skill[x].custom_skill}</p>`
                          }
                        str += `</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <p class="m-0">{{ __('general.resume') }}</p>
                    <div class="mt-3">
                      <p class="m-0 mt-3">{{ __('general.work_experience') }}</p>`
                      if(response.data.resume.length > 0){
                        for(let x in response.data.resume[0].experience)
                          str += `<div class="mt-1">
                            <p class="m-0">${response.data.resume[0].experience[x].name}</p>
                            <p class="m-0">${response.data.resume[0].experience[x].start_year} - ${response.data.resume[0].experience[x].end_year}</p>
                            <div class="d-flex align-items-center">
                              <div class="d-flex align-items-center">
                                <i class="fa-solid fa-building"></i>
                                <p class="m-0 ml-1">${response.data.resume[0].experience[x].corporation}</p>
                              </div>

                              <div class="d-flex align-items-center ml-3">
                                <i class="fa-solid fa-location-dot"></i>
                                <p class="m-0 ml-1">${response.data.resume[0].experience[x].city.name}</p>
                              </div>
                            </div>
                            <p class="m-0">${response.data.resume[0].experience[x].description}</p>
                          </div>`
                      }
                    str += `</div>
                  </div>
                </div>
              </div>
            </div>
          `
          $('#resume_data').html(str)
        }
      }
    }

    $(document).ready(() => {
      $('#qr_content').keyup(() => {
        if($('#qr_content').val().length >= 20)
          get_user()
      })
    })
  </script>
@endpush
