<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>BoolBnB</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        {{-- Fonts awesome --}}
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- CSS Libreria DROPZoNE drag and drop file -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" rel="stylesheet">
        {{-- BRAINTREE PAYMENT --}}
        <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>


    </head>
    <body>

        @yield('content_header')
      <div class="my-container">
        @yield('content')
      </div>

        </div>

        {{-- Libreria DROPZONE --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    </body>
</html>
