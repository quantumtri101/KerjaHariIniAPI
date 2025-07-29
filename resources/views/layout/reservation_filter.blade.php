<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <div>
        <div class="d-flex">
          <button class="btn btn-outline-info active" id="all">{{ __('general.all') }}</button>
          <button class="btn btn-outline-info ml-1" id="by_range_date">{{ __('general.by_range_date') }}</button>
          <button class="btn btn-outline-info ml-1" id="by_single_date">{{ __('general.by_single_date') }}</button>

          <div class="ml-1" id="range_date_container">
            <div class="d-flex align-items-center">
              <div class="w-100">
                <input type="text" id="start_date" class="form-control datetimepicker-input" onkeydown="return false;" data-toggle="datetimepicker"/>
              </div>
              <div class="ml-3">
                <p class="m-0">-</p>
              </div>
              <div class="ml-3 w-100">
                <input type="text" id="end_date" class="form-control datetimepicker-input" onkeydown="return false;" data-toggle="datetimepicker" />
              </div>
            </div>
          </div>

          <div class="ml-1" id="single_date_container">
            <div class="d-flex align-items-center">
              <div class="w-100">
                <input type="text" id="single_date" class="form-control datetimepicker-input" onkeydown="return false;" data-toggle="datetimepicker"/>
              </div>
            </div>
          </div>
        </div>

        
      </div>

      <div class="d-flex align-items-start ml-3">
        <a class="btn btn-primary" id="excel_btn">Excel</a>
        <a class="btn btn-primary ml-1" id="pdf_btn">PDF</a>
        {{-- <button class="btn btn-primary ml-1" id="print_btn">Print</button> --}}
      </div>
    </div>
  </div>
</div>

@push('script')
<script type="text/javascript">
  var month = '{{ Carbon\Carbon::now()->formatLocalized("%B %Y") }}'
  var start_date = ''
  var end_date = ''
  var single_date = ''
  var filter_type = "all"
  var excel_url = ''
  var pdf_url = ''

  function init_month(){
    $('#month').datetimepicker('destroy')
    $('#month').datetimepicker({
      format: 'MMMM YYYY',
      defaultDate: moment(month, 'MMMM YYYY'),
    })

    $('#month').on("change.datetimepicker", ({date, oldDate}) => {
      if(oldDate != null){
        month = $('#month').val()
        // init_datatable()
      }
    })
  }

  function init_single_date(){
    $('#single_date').datetimepicker('destroy')
    $('#single_date').datetimepicker({
      format: 'DD/MM/YYYY',
      defaultDate: single_date !== "" ? moment(single_date, 'DD/MM/YYYY') : moment(),
    })

    $('#single_date').on("hide.datetimepicker", () => {
      single_date = $('#single_date').val()
    })

    $('#single_date').on("change.datetimepicker", ({date, oldDate}) => {
      if(oldDate != null){
        single_date = $('#single_date').val()
      }
    })
    single_date = moment().format('DD/MM/YYYY')
  }

  function init_start_date(){
    $('#start_date').datetimepicker('destroy')
    $('#start_date').datetimepicker({
      format: 'DD/MM/YYYY',
      defaultDate: start_date !== "" ? moment(start_date, 'DD/MM/YYYY') : moment(),
      maxDate: end_date !== "" ? moment(end_date, 'DD/MM/YYYY') : moment(),
    })

    $('#start_date').on("hide.datetimepicker", () => {
      start_date = $('#start_date').val()
    })

    $('#start_date').on("change.datetimepicker", ({date, oldDate}) => {
      if(oldDate != null){
        start_date = $('#start_date').val()
        init_end_date()
      }
    })
  }

  function init_end_date(){
    $('#end_date').datetimepicker('destroy')
    $('#end_date').datetimepicker({
      format: 'DD/MM/YYYY',
      defaultDate: end_date !== "" ? moment(end_date, 'DD/MM/YYYY') : moment().add(1, 'd'),
      minDate: start_date !== "" ? moment(start_date, 'DD/MM/YYYY') : moment(),
    })

    $('#end_date').on("hide.datetimepicker", () => {
      end_date = $('#end_date').val()
    })

    $('#end_date').on("change.datetimepicker", ({date, oldDate}) => {
      if(oldDate != null){
        end_date = $('#end_date').val()
        init_start_date()
        // get_data()
      }
    })
  }

  function manage_filter_type(){
    if(filter_type === "all"){
      $('#all').addClass('active')
      $('#by_range_date').removeClass('active')
      $('#by_single_date').removeClass('active')
      $('#range_date_container').addClass('d-none')
      $('#single_date_container').addClass('d-none')

      start_date = ""
      end_date = ""
      single_date = ""
    }
    else if(filter_type === "by_range_date"){
      $('#all').removeClass('active')
      $('#by_range_date').addClass('active')
      $('#by_single_date').removeClass('active')
      $('#range_date_container').removeClass('d-none')
      $('#single_date_container').addClass('d-none')
    }
    else if(filter_type === "by_single_date"){
      $('#all').removeClass('active')
      $('#by_range_date').removeClass('active')
      $('#by_single_date').addClass('active')
      $('#range_date_container').addClass('d-none')
      $('#single_date_container').removeClass('d-none')
    }
  }

  $(document).ready(function () {
    init_month()
    
    
    manage_filter_type()

    $('#excel_btn').click(() => {
      var url = '{{ !empty($excel_url) ? $excel_url : '' }}'
      if(url === "" && excel_url !== "")
        url = excel_url
      if(start_date !== "" && end_date !== "" || single_date !== ""){
        url += url.includes('?') ? '&' : '?'
        if(start_date !== "" && end_date !== "")
          url += 'start_date=' + start_date + '&end_date=' + end_date
        else if(single_date !== "")
          url += 'date=' + single_date
      }
      
      window.open(url)
      // datatable.button('0').trigger()
    })

    $('#pdf_btn').click(() => {
      var url = '{{ !empty($pdf_url) ? $pdf_url : '' }}'
      if(url === "" && pdf_url !== "")
        url = pdf_url
      if(start_date !== "" && end_date !== "" || single_date !== ""){
        url += url.includes('?') ? '&' : '?'
        if(start_date !== "" && end_date !== "")
          url += 'start_date=' + start_date + '&end_date=' + end_date
        else if(single_date !== "")
          url += 'date=' + single_date
      }
      window.open(url)
      // datatable.button('1').trigger()
    })

    // $('#print_btn').click(() => {
    //   datatable.button('2').trigger()
    // })

    $('#all').click(() => {
      filter_type = "all"
      manage_filter_type()
    })

    $('#by_range_date').click(() => {
      filter_type = "by_range_date"
      manage_filter_type()
      init_start_date()
    })

    $('#by_single_date').click(() => {
      filter_type = "by_single_date"
      manage_filter_type()
      init_single_date()
    })
  })
</script>
@endpush