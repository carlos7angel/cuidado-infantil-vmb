<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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

    <link rel="shortcut icon drdfgfdg" href="{{ asset('themes/common/media/logos/favicon/favicon.ico') }}" />
    <link href="{{ asset('themes/common/css/style.font.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/common/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('themes/admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')

    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
</head>

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">

<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

        @include('vendor@template::admin.components.base._header')

        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <div id="kt_app_toolbar" class="app-toolbar py-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
                    <div class="d-flex flex-column flex-row-fluid">
                        <div class="d-flex align-items-center pt-1">
                            {{--include('vendor@template::external.components.partials._breadcrumbs')--}}
                            @yield('breadcrumbs')
                        </div>
                        <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-18 py-lg-13">
                            {{--@include('vendor@template::external.components.partials._subheader')--}}
                            @yield('headline')
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-container container-xxl">
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @yield('content')
                    </div>
                    @include('vendor@template::admin.components.base._footer')
                </div>
            </div>
        </div>
    </div>
</div>

@yield('modals')

@include('vendor@template::admin.components.partials._scrolltop')

<script>var hostUrl = "/";</script>
<script src="{{ asset('themes/common/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('themes/admin/js/scripts.bundle.js') }}"></script>
@yield('scripts')
</body>

</html>