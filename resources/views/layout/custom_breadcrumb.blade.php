<nav class="breadcrumb pd-0 mg-0 tx-12" id="custom_breadcrumb">
  @foreach($arr as $key => $data)
    @if($key == count($arr) - 1)
      <span class="breadcrumb-item active">{{ $data }}</span>
    @else
      <a class="breadcrumb-item bc-click" href="#" index="{{ count($arr) - 1 - $key }}">{{ $data }}</a>
    @endif
  @endforeach
</nav>

@push('script')
  <script>
    var arr_stack_title = []
    $(document).ready(() => {
      arr_stack_title = window.localStorage.getItem('arr_stack_title') != null ? JSON.parse(window.localStorage.getItem('arr_stack_title')) : []

      var str = ''
      for(let x in arr_stack_title){
        str += `<a class="breadcrumb-item bc-click" href="#" index="${arr_stack_title.length - x}">${arr_stack_title[x]}</a>`
      }
      str += `<span class="breadcrumb-item active">{{ !empty($title) ? $title : '' }}</span>`
      $('#custom_breadcrumb').html(str)

      $('.bc-click').click(function() {
        var index = $(this).attr('index')
        for(let x = 0; x < index; x++){
          back_page(x == index - 1)
        }
      })
    })
  </script>
@endpush