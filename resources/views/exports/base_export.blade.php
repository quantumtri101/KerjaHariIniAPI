<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
  html{
    font-size: 16px;
  }
  body{
    font-family: inter-regular;
/*    margin-top: 2.5cm;*/
  }
  
  .nav-item.active .nav-link, .collapse-item.active, .collapse-item:hover {
    background-color: #FFFFFF1A !important;
  }
  .nav-link[data-toggle=collapse]::after{
    color: #FFFFFF !important;
  }
  .card{
    border-radius: 1rem;
  }
  a, .page-link{
    color: #6F826E;
  }
  .text-primary{
    color: #6F826E !important;
  }
  .nav-pills .nav-link.active, .bg-primary{
    background-color: #6F826E !important;
  }
  .btn-primary, .page-item.active .page-link, .btn-primary:hover, .btn-primary:focus, .btn-outline-dark:focus{
    background-color: #6F826E;
    border-color: #6F826E;
  }
  .btn-success, .btn-success:hover, .btn-success:focus, .bg-success, .badge-success{
    background-color: #B1CF24;
    border-color: #B1CF24;
  }
  .btn-success.disabled, .btn-success:disabled{
    background-color: #EBEFE2;
    border-color: #EBEFE2;
  }
  .btn-outline-dark{
    color: #6F826E;
    border-color: #6F826E;
  }
  .form-group label{
    color: #000000;
    font-size: 1rem;
  }
  .form-group .form-control{
    color: #000000;
    font-size: .9rem;
  }
  th div{
    color: #6B7280;
    font-size: .9rem;
  }
  td{
    color: #111827;
    font-size: .9rem;
  }
  td .btn{
    /* color: #FFFFFF; */
    font-size: .9rem;
  }
  p{
    line-height: 150%;
  }
  input:focus{
    outline: none;
  }
  .modal-content{
    border-radius: 1rem;
  }
  @media print {
    .new-page {
      page-break-before: always;
    }
  }
  .page-break {
    page-break-after: always;
  }
  .pb_before {
    page-break-before: always !important;
  }

  .pb_after {
    page-break-after: always !important;
  }
  .table th, .table td{
    vertical-align: middle !important;
    padding: .5rem;
  }
  header {
    position: fixed;
    top: -.5cm;
    left: 0cm;
    right: 0cm;
    height: 2cm;
  }
  .watermark {
    position: fixed;
    top: 10cm;
    left: 6.2cm;
    right: 0cm;
    z-index:  -1000;
    opacity: 0.5;
  }
  .table-bordered td, .table-bordered th{
    border: 2px solid black;
  }
  .comment *{
    font-family: inter-regular !important;
    font-size: .7rem !important;
    text-align: justify !important;
  }
  </style>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    function str_to_double(data){
      var value
      if(data != '')
        value = parseFloat(data.replace(/\./g,''))
      else
        value = 0
      return value
    }

    function to_currency_format(data){
      var value = str_to_double(data)

      return value.toLocaleString(locale_string)
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
                location.href = "{{ url('/auth/login') }}"
              }
            })
        }
        else if(method === 'post')
          response = await axios.post(url, data, {
            headers: {
              "Content-Type": "application/json",
            },
            onUploadProgress
          })
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ url('/auth/login') }}"
              }
            })
        else if(method === 'put')
          response = await axios.put(url, data, {
            headers: {
              "Content-Type": "application/json",
            },
            onUploadProgress
          })
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ url('/auth/login') }}"
              }
            })
        else if(method === 'delete')
          response = await axios.delete(url)
            .catch(function (error) {
              if (error.response && error.response.status == 401) {
                location.href = "{{ url('/auth/login') }}"
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

    function notify_user(message, e = null){
      alert(message)
      if(e != null)
        e.preventDefault()
    }
  </script>
  @yield('top_script')
</head>

<body>

  @yield('content')

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>

  @yield('script')
</body>

</html>
