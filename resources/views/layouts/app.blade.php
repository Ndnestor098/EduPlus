<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=6">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- =======================================DESCRIPCION======================================= -->
        <title>{{ $title }} - EduPlus</title>
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

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://kit.fontawesome.com">
        <script src="https://kit.fontawesome.com/8f34396e62.js" crossorigin="anonymous"></script>

        <!-- =======================================FAVICON======================================= -->
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="/assets/img/favicon/site.webmanifest">
        <link rel="mask-icon" href="/assets/img/favicon/safari-pinned-tab.svg" color="#273746">
        <link rel="shortcut icon" href="/assets/img/favicon/favicon.ico">
        <meta name="apple-mobile-web-app-title" content="EduPlus">
        <meta name="application-name" content="EduPlus">
        <meta name="msapplication-TileColor" content="#ffc40d">
        <meta name="msapplication-config" content="/assets/img/favicon/browserconfig.xml">
        <meta name="theme-color" content="#eaf2f8">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            {{$navigation}}

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
