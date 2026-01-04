<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <base href="/" />
    <title>@yield('title', $page_title ?? '') | {{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="@yield('title', $page_title ?? '')" />
    <meta property="og:url" content="/" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <link rel="canonical" href="/" />

{{--    <link rel="shortcut icon" href="{{ asset('themes/common/media/logos/favicon/favicon.ico') }}" />--}}
    <link href="{{ asset('themes/common/css/style.font.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/common/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
{{--    <link href="{{ asset('themes/admin/css/style.admin.css') }}" rel="stylesheet" type="text/css" />--}}
    @yield('styles')
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
</head>
<body id="kt_body" class="app-blank">
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            @yield('content')
        </div>
        <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset('themes/common/media/auth/bg7.jpg') }});">
            <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">

                <div class="d-flex justify-content-center align-items-center mb-0 mb-lg-12">
                    <a class="mx-3">
                        <img alt="Logo" src="{{ asset('themes/common/media/logos/logo.png') }}" class="h-80px h-lg-100px" />
                    </a>
                </div>

                <h1 class="d-none d-lg-block fs-1qx fw-bolder text-center mb-9 mt-2" style="color: #0f2283;">
                    SISTEMA DE SEGUIMIENTO Y MONITOREO DE CENTROS DE CUIDADO INFANTIL
                </h1>
{{--                <h1 class="d-none d-lg-block fs-2qx text-center mb-7" style="font-weight: 800; color: #0f2283">SISMOPP</h1>--}}

                <div class="d-none d-lg-block text-gray-800 fs-base text-center">
                    Sistema integral para la gestión de información de los procesos de Registro, Monitoreo y Evaluación de los Centros de Cuidado Infantil (CCI) de los Gobiernos Municipales.
                </div>

                <div class="d-flex justify-content-end mb-0 mb-lg-12 w-100">
                    <a class="mt-5 mt-lg-12 d-flex flex-column">
                        <span class="w-100 text-end text-gray-700 fw-bold pb-2">Impulsado por:</span>
                    </a>
                </div>

                <div class="d-flex justify-content-center align-items-center mb-0 mb-lg-12">
                    <a class="mx-3">
                        <img alt="Logo" src="{{ asset('themes/common/media/logos/logo_vmb.png') }}" class="h-30px h-lg-40px h-xs-30px" />
                    </a>
                    <a class="mx-3">
                        <img alt="Logo" src="{{ asset('themes/common/media/logos/logo_koica.png') }}" class="h-30px h-lg-40px h-xs-30px" />
                    </a>
                    <a class="mx-3">
                        <img alt="Logo" src="{{ asset('themes/common/media/logos/logo_onu.png') }}" class="h-30px h-lg-40px h-xs-30px" />
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<script>var hostUrl = "/";</script>
<script src="{{ asset('themes/common/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('themes/admin/js/scripts.bundle.js') }}"></script>
@yield('scripts')
</body>
</html>