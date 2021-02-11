<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        </head>
        <div id="app">
            <!-- デフォルトだとこの中ではvue.jsが有効 -->
            <!-- example-component はLaravelに入っているサンプルのコンポーネント -->
            <example-component></example-component>
        </div>
    </body>
    <script src=" {{ mix('js/app.js') }} "></script>
</html>