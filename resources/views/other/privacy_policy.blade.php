<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy Policy</title>
  <style type="text/css">
    .ml-1 {
      max-width: 80ch;
    }
    .ml-2 {
      max-width: 70ch;
    }
    .indent {
      text-indent: 2rem;
    }
    .bold {
      font-weight: bold;
    }
    .linone {
      list-style-type: none;
    }
    .inline {
      display: inline;
    }
    body {
      margin: 1rem;
      line-height: 1.3;
    }
    li::marker{
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div>
    {{-- <h1>{{ $privacy_policy->title }}</h1> --}}
    <div>{!! $privacy_policy->content !!}</div>
  </div>
</body>
</html>
