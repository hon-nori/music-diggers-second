<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sample') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>
    <div id="app">
       <router-view />
    </div>
    <script> 
        var api_key = '{{Config::get('const.LAST_FM_API_KEY')}}';
        window.Laravel = <?php echo json_encode([ 
        'csrfToken' => csrf_token(), 
        ]); ?>;
    </script> 
</body>
</html>