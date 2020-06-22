<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="token" content="{{ Session::get('token') }}">

        <title>Sinader</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
       <div id='App'>
       </div>
      <script src="{{ asset('js/app.js') }}" type="text/javascript"></script> 
    </body>
</html>