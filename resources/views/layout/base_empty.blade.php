<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=0.3">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ $web_admin_name }} v{{ $app_version }}</title>

  <link rel="icon" type="image/x-icon" href="{{ $url_asset.'/image/no_image_available.jpeg' }}">
  <!-- Custom fonts for this template-->
  <link href="{{ $url_asset.'/sb-admin/vendor/fontawesome-free/css/all.min.css' }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ $url_asset.'/css/App.css' }}" rel="stylesheet">
  <link href="{{ $url_asset.'/asset/datatables.net-dt/css/jquery.dataTables.min.css' }}" rel="stylesheet">
  <link href="{{ $url_asset.'/asset/datatables.net-responsive-dt/css/responsive.dataTables.min.css' }}" rel="stylesheet">

  <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
  {{-- <link rel="stylesheet" type="text/css" href="https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.css" /> --}}
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" />
  <link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet">
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" /> --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <script>
    var locale_string = 'id-ID'
    var format_date = 'DD/MM/YYYY'
    var wait_search_time = 1000
    var start_time = '08:00'
    var end_time = '14:00'
    var interval = 15
    var interval_unit = 'm'

    function alertDelete(url, confirm_text = 'Are you sure to delete this data?'){
      confirm_text = confirm_text.replace('--n', "\n")
      if(confirm(confirm_text)){
        location.href = url
      }
    }
    
    function reset_page_stack(){
      window.localStorage.setItem('arr_stack_page', JSON.stringify([]))
      window.localStorage.setItem('menu', '')
      window.localStorage.setItem('menu1', '')
    }

    function save_current_page(){
      var arr_stack_page = window.localStorage.getItem('arr_stack_page') != null ? JSON.parse(window.localStorage.getItem('arr_stack_page')) : []
      arr_stack_page.push('{{ url()->full() }}')
      window.localStorage.setItem('arr_stack_page', JSON.stringify(arr_stack_page))
    }

    function back_page(with_redirect = true){
      var arr_stack_page = window.localStorage.getItem('arr_stack_page') != null ? JSON.parse(window.localStorage.getItem('arr_stack_page')) : []
      var page = arr_stack_page[arr_stack_page.length - 1]
      arr_stack_page.pop()
      window.localStorage.setItem('arr_stack_page', JSON.stringify(arr_stack_page))
      if(with_redirect)
        location.href = page
    }

    function check_phone_format(data){
      return data.length > 0 && data[data.length - 1].match(/^[\d\s]+$/g) == null ? data.substring(0, data.length - 1) : data
    }

    function str_to_double(data, default_value = '0'){
      var value
      if(data != '')
        value = parseFloat(data.replace(/\./g,'').replace(/,/g,'.'))
      else
        value = default_value
      return value
    }

    function phone_validation(data, max_length = 12){
      data = String(str_to_double(data, ''))
      if(isNaN(data))
        data = '0'
      if(data.charAt(0) === '0')
        data = data.slice(1)
      if(max_length > 0 && data.length > max_length)
        data = data.substring(0, max_length)
      return data
    }

    function url_validation(string) {
      var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
      return (res !== null)
    }

    function to_currency_format(data, max_number = 999999999, max_comma_length = 2){
      var value = data
      
      if(value[value.length - 1] !== ","){
        var is_include_comma = false
        var is_convert_double = true
        var index_comma = 0
        for(let x = 0; x < value.length; x++){
          if(value[x] === ","){
            is_include_comma = true
            index_comma = x
          }
          else if(is_include_comma && x == value.length - 1 && value[x] === "0")
            is_convert_double = false
        }

        if(is_include_comma){
          is_convert_double = value.length - index_comma > max_comma_length && value[value.length - 2] !== "0"
          value = value.substring(0, index_comma + 1 + max_comma_length)
        }
        
        
        if(is_convert_double){
          value = str_to_double(value)
          if(isNaN(value))
            value = 0
          if(value > max_number)
            value = max_number
        }
      }

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

    function getBase64FromURL(url, callback) {
      var xhr = new XMLHttpRequest();
      xhr.onload = function(e){ //Stringify blob...
        //reload the icon from storage
        callback(xhr.response)
      }
      xhr.open('GET', url, true);
      xhr.responseType = "blob";
      xhr.send();
    }

    async function request(url, method = "get", data = {}, with_modal = true, onUploadProgress = response => {}){
      try{
        if(with_modal)
          $('#please_wait_modal').modal('show')
        axios.defaults.headers.common['Accept'] = 'application/json'
        data['_token'] = '{{ csrf_token() }}'

        var response
        if(method === 'get'){
          for(let x in data)
            url += (url.includes('?') ? '&' : '?') + x + "=" + (Array.isArray(data[x]) ? JSON.stringify(data[x]) : data[x])
          response = await axios.get(url)
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ $url_asset.'/login' }}"
              }
            })
        }
        else if(method === 'post'){
          var form_data = new FormData()
          for(let x in data){
            if(Array.isArray(data[x])){
              for(let y in data[x])
                form_data.append(x + "[" + y + "]", data[x][y])
            }
            else
              form_data.append(x, data[x])
          }

          response = await axios.post(url, form_data, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
            onUploadProgress
          })
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ $url_asset.'/login' }}"
              }
            })
        }
        else if(method === 'put')
          response = await axios.put(url, data, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
            onUploadProgress
          })
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ $url_asset.'/login' }}"
              }
            })
        else if(method === 'delete')
          response = await axios.delete(url)
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ $url_asset.'/login' }}"
              }
            })


        if(with_modal){
          setTimeout(() => {
            $('#please_wait_modal').modal('hide')
          }, 500)
        }
        return response.data
      } catch(e){
        if(with_modal){
          setTimeout(() => {
            $('#please_wait_modal').modal('hide')
          }, 500)
        }
        notify_user(e.message)
      }
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
    .select2-selection__rendered {
      line-height: 2.6rem !important;
    }
    .select2-container .select2-selection--single {
      height: 2.6rem !important;
    }
    .select2-selection__arrow {
      height: 2.6rem !important;
    }
    .table .btn{
      padding: .3rem .5rem;
    }
  </style>

  @stack('top_script')
</head>

<body id="page-top">

  <div class="">
    <div id="wrapper">
      @include('layout.navbar_empty')

      <div class="br-mainpanel ml-0">

        @if(Session::has('message'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <div class="p-5">
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="d-block d-lg-none">
    <div class="d-flex justify-content-center align-items-center h-100">
      {{ __('general.please_turn_to_desktop' }}
    </div>
  </div> --}}

  @include('layout.modal.please_wait')
  <!-- Bootstrap core JavaScript-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="{{ $url_asset.'/sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js' }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ $url_asset.'/sb-admin/vendor/jquery-easing/jquery.easing.min.js' }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ $url_asset.'/js/bracket.js' }}"></script>

  <script src="{{ $url_asset.'/asset/perfect-scrollbar/perfect-scrollbar.min.js' }}"></script>
  {{-- <script src="{{ $url_asset.'/asset/datatables.net/js/jquery.dataTables.min.js' }}"></script>
  <script src="{{ $url_asset.'/asset/datatables.net-dt/js/dataTables.dataTables.min.js' }}"></script>
  <script src="{{ $url_asset.'/asset/datatables.net-responsive/js/dataTables.responsive.min.js' }}"></script>
  <script src="{{ $url_asset.'/asset/datatables.net-responsive-dt/js/jquery.dataTables.min.js' }}"></script> --}}

  <!-- Page level plugins -->
  {{-- <script src="{{ $url_asset.'/sb-admin/vendor/chart.js/Chart.min.js' }}"></script> --}}

  <!-- Page level custom scripts -->
  {{-- <script src="{{ $url_asset.'/sb-admin/js/demo/chart-area-demo.js' }}"></script>
  <script src="{{ $url_asset.'/sb-admin/js/demo/chart-pie-demo.js' }}"></script> --}}

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  {{-- <script src="https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.js"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

  @stack('script')

</body>

</html>
