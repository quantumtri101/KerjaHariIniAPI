@extends('layout.base')

@section('breadcrumb')
  @include('layout.custom_breadcrumb', [
    "arr" => [],
    "title" => __($lang_file.'.detail'),
  ])
@endsection

@section('content')
  @include('layout.custom_navigation', [
    "title" => __($lang_file.'.detail'),
  ])

  @if($jobs->is_approve == -2)
    <div class="alert alert-warning mt-3" role="alert">
      {{ __('general.jobs_client_decline_warning') }}
    </div>
  @endif

  @if($jobs->num_people_required > count($jobs->application))
  <div class="mt-3">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#scan_qr_ots_modal">
      {{ __('general.scan_qr_ots') }}
    </button>
  </div>
  @endif

  <div class="mt-3">
    <ul class="nav nav-pills" style="overflow-x: scroll; flex-wrap: nowrap;" id="detailTab" role="tablist">
      @foreach($arr_tab as $tab)
        <li class="nav-item" role="presentation">
          <button class="nav-link border-0" id="{{ $tab["id"] }}-tab" data-toggle="tab" data-target="#{{ $tab["id"] }}" type="button" role="tab" onclick="on_tab_clicked('{{ $tab["id"] }}')" aria-controls="{{ $tab["id"] }}" aria-selected="true">{{ __('general.'.$tab["id"]) }}</button>
        </li>
      @endforeach
    </ul>

    <div class="mt-3 d-none" id="filter_container">
      @include('layout.reservation_filter',[
      ])
    </div>
    
    <!-- Tab panes -->
    <div class="tab-content mt-3" id="pills-detailTabContent">
      @foreach($arr_tab as $tab)
      <div class="tab-pane" id="{{ $tab["id"] }}" role="tabpanel" aria-labelledby="{{ $tab["id"] }}-tab">
        @include($tab["component"])
      </div>
      @endforeach
    </div>
  </div>
</div>

  @include('jobs_application.component.modal.modal_applied')
  @include('layout.modal.jobs_upload_pkhl')
  @include('layout.modal.jobs_upload_pkwt')
  @include('layout.modal.scanQR_OTS')
  @include('layout.modal.jobs_decline')

  @push('script')
    <script>
      function on_tab_clicked(id){
        localStorage.setItem('menu', id)
      }

      function showDeclineModal(jobs_id){
        $('#jobs_decline_jobs_id').val(jobs_id)
        $('#jobs_decline_modal').modal('show')
      }

      function showUploadDocumentModal(jobs_id){
        $('#jobs_document_jobs_id').val(jobs_id)
        $('#extension_notice').html('{{ __("general.upload_document_extension") }}')
        $('#jobs_upload_document_modal').modal('show')
      }
      
      $(document).ready(async() => {
        var menu = await get_menu_detail()
          
        localStorage.setItem('menu', menu)
          
        $('#' + menu + '-tab').addClass('active')
        $('#' + menu).addClass('show active')
      })
    </script>
  @endpush
@endsection
