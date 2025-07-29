<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ $web_admin_name }}</title>

  <link rel="icon" type="image/x-icon" href="{{ $url_asset.'/image/no_image_available.jpeg' }}">
  <!-- Custom fonts for this template-->
  <link href="{{ $url_asset.'/sb-admin/vendor/fontawesome-free/css/all.min.css' }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Custom styles for this template-->
  <link href="{{ $url_asset.'/sb-admin/css/sb-admin-2.min.css' }}" rel="stylesheet">

  <link href="{{ $url_asset.'/css/bracket.css' }}" rel="stylesheet">

  <script>
    var locale_string = 'id-ID'
    var format_date = 'DD/MM/YYYY'
    var wait_search_time = 1000
    var start_time = '08:00'
    var end_time = '14:00'
    var interval = 15
    var interval_unit = 'm'

    function reset_page_stack(){
      window.localStorage.setItem('arr_stack_page', JSON.stringify([]))
    }

    function save_current_page(){
      var arr_stack_page = window.localStorage.getItem('arr_stack_page') != null ? JSON.parse(window.localStorage.getItem('arr_stack_page')) : []
      arr_stack_page.push('{{ url()->full() }}')
      window.localStorage.setItem('arr_stack_page', JSON.stringify(arr_stack_page))
    }

    function back_page(){
      var arr_stack_page = window.localStorage.getItem('arr_stack_page') != null ? JSON.parse(window.localStorage.getItem('arr_stack_page')) : []
      var page = arr_stack_page[arr_stack_page.length - 1]
      arr_stack_page.pop()
      window.localStorage.setItem('arr_stack_page', JSON.stringify(arr_stack_page))
      location.href = page
    }

    function check_phone_format(data){
      return data.length > 0 && data[data.length - 1].match(/^[\d\s]+$/g) == null ? data.substring(0, data.length - 1) : data
    }

    function to_currency_format(data){
      var value
      if(data != '')
        value = parseFloat(data.replace(/\./g,''))
      else
        value = 0

      return value.toLocaleString(locale_string)
    }

    function getBase64(file, callback) {
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function () {
        callback(reader.result)
      };
      reader.onerror = function (error) {
        console.log('Error: ', error);
      };
    }

    async function request(url, method = "get", data = {}, with_modal = true, onUploadProgress = response => {}){
      if(with_modal)
        $('#please_wait_modal').modal('show')
      axios.defaults.headers.common['Accept'] = 'application/json'
      data['_token'] = '{{ csrf_token() }}'

      var response
      if(method === 'get'){
        for(let x in data)
          url += (url.includes('?') ? '&' : '?') + x + "=" + (Array.isArray(data[x]) ? JSON.stringify(data[x]) : data[x])
        response = await axios.get(url)
      }
      else if(method === 'post')
        response = await axios.post(url, data, {
          headers: {
            "Content-Type": "application/json",
          },
          onUploadProgress
        })
      else if(method === 'put')
        response = await axios.put(url, data, {
          headers: {
            "Content-Type": "application/json",
          },
          onUploadProgress
        })
      else if(method === 'delete')
        response = await axios.delete(url)


      if(with_modal){
        setTimeout(() => {
          $('#please_wait_modal').modal('hide')
        }, 500)
      }
      return response.data
    }

    function manage_select(element, value){
      $(element).val(value)
      $(element).trigger('change')
    }

    function notify_user(message, e = null){
      alert(message)
      if(e != null)
        e.preventDefault()
    }

    function validate_email(email){
      return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
    }

    function validate_phone(phone){
      return phone.length >= 6;
    }
  </script>

  <style>
    html, body {
      height: 100%;
      font-size: 14px;
    }
    @media screen and (min-width: 960px) {
      .tree-size {
        height: 40rem;
        left: -25rem;
      }
      .tree-size-2 {
        height: 40rem;
        right: -20rem;
        bottom: -20rem
      }
    }
    @media screen and (max-width: 720px) {
      .tree-size {
        height: 20rem;
        top: 10rem;
        left: -12rem;
      }
      .tree-size-2 {
        height: 20rem;
        right: -10rem;
        bottom: -10rem
      }
    }
    input:focus{
      outline:none;
    }
    .login-logo, .register-logo{
      font-size: 2.1rem;
      font-weight: 300;
      margin-bottom: 0.9rem;
      text-align: center;
    }
  </style>
</head>

<body id="page-top">
  <div class="position-relative h-100">

    <div class="h-100">
      

      <!-- Outer Row -->
      @yield('content')
    </div>

    {{-- <div class="container h-100 d-block d-lg-none">
      <div class="d-flex justify-content-center align-items-center h-100">
        {{ __('general.please_turn_to_desktop' }}
      </div>
    </div> --}}
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ $url_asset.'/sb-admin/vendor/jquery/jquery.min.js' }}"></script>
  <script src="{{ $url_asset.'/sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js' }}"></script>

  <script src="{{ $url_asset.'/js/bracket.js' }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ $url_asset.'/sb-admin/vendor/jquery-easing/jquery.easing.min.js' }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ $url_asset.'/sb-admin/js/sb-admin-2.min.js' }}"></script>

  <!-- Page level plugins -->
  <script src="{{ $url_asset.'/sb-admin/vendor/chart.js/Chart.min.js' }}"></script>

  <!-- Page level custom scripts -->
  <script src="{{ $url_asset.'/sb-admin/js/demo/chart-area-demo.js' }}"></script>
  <script src="{{ $url_asset.'/sb-admin/js/demo/chart-pie-demo.js' }}"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>

  @stack('script')
</body>

</html>
