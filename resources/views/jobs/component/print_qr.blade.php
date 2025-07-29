<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
  </head>
  <body>
    <div>
      <p class="text-center">{{ $jobs->id }}</p>
      <p class="text-center">{{ $jobs->name }} @ {{ $jobs_shift->start_date->formatLocalized('%d %B %Y') }}</p>
      <div class="visible-print text-center">
        <img src="data:image/png;base64, {!! $qr_code !!}"/>
      </div>
    </div>
  </body>
</html>
