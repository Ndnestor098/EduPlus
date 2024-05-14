<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=6">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- =======================================DESCRIPCION======================================= -->
        <title>{{ $title }} - ACIZ</title>
        <meta name="description" content="Vendemos zapatos de tu preferencias para que estes comodo y contento con tus elecciones.">
        
        <meta name="product_id" content="102856451">
        <meta property="fb:admins" content="100000984792126">
        <meta property="fb:app_id" content="255986568181021">

        <!-- =======================================INSTAGRAM======================================= -->
        <meta property="og:site_name" content="Scarpetoss">
        <meta property="og:description" content="Vendemos zapatos de tu preferencias para que estes comodo y contento con tus elecciones.">
        <meta property="og:title" content="Tienda online - Scarpetoss">
        <meta property="og:url" content="{{request()->url()}}">
        <meta property="og:image" content="/image/logo.jpeg">
        <meta property="og:updated_time" content="2024-01-28T10:35:47+00:00">
        <meta property="og:type" content="website">

        <!-- =======================================TWITTER======================================= -->
        <meta property="twitter:card" content="summary">
        <meta property="twitter:site_name" content="Scarpetoss">
        <meta property="twitter:description" content="Vendemos zapatos de tu preferencias para que estes comodo y contento con tus elecciones.">
        <meta property="twitter:title" content="Tienda online - Scarpetoss">
        <meta property="twitter:url" content="{{request()->url()}}">
        <meta property="twitter:image" content="/image/logo.jpeg">
        <meta property="twitter:updated_time" content="2024-01-28T10:35:47+00:00">

        <!-- =======================================PRECONECCIONES	======================================= -->
        <meta rel="canonical" href="{{request()->url()}}">
        <meta name="robots" content="NOODP,NOYDIR">

        <link rel="stylesheet" href="/css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://kit.fontawesome.com">
        <script src="https://kit.fontawesome.com/8f34396e62.js" crossorigin="anonymous"></script>

        <!-- =======================================FAVICON======================================= -->
        <link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/assets/img/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/assets/img/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
        {{-- <link rel="manifest" href="/assets/img/favicon/manifest.json"> --}}
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/assets/img/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <img src="/assets/img/logo.png" alt="Logo" style="width:170px; height:200px">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
