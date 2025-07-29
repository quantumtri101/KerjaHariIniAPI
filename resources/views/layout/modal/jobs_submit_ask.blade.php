<div class="modal fade" id="jobs_submit_ask_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('general.jobs_submit_ask') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <button type="button" class="btn btn-primary" data-dismiss="modal" @click="only_save">Save Only</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" @click="save_publish">Save & Publish</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    var jobs_submit_ask_modal = new Vue({
      el: '#jobs_submit_ask_modal',
      data: {
      },
      methods: {
        only_save(){
          back_page(false)
          $('#jobs_type').val('only_save')
          $('#jobsForm').trigger('submit')
        },
        save_publish(){
          $('#jobs_type').val('save_publish')

          publish_start_date()
          publish_end_date()
          $('#jobs_submit_ask_modal').modal('hide')
          $('#publish_date_add_jobs').modal('show')
        },
      },
    })
  </script>
@endpush
