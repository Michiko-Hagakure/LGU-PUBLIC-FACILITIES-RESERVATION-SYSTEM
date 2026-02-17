<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LGU1 - Authentication')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00473e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="LGU1 PFRS">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Fonts (PROJECT_DESIGN_RULES.md) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets (CSS + Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Inline fallback: replicate Bootstrap + Bootstrap Icons when CDN unavailable (offline-first) -->
    <style id="bootstrap-fallback-css">
        /* === Bootstrap Reset & Base === */
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1rem; line-height: 1.5; color: #212529; background-color: #fff; -webkit-font-smoothing: antialiased; }
        a { color: #0d6efd; text-decoration: underline; }
        img { vertical-align: middle; max-width: 100%; }
        h1, h2, h3, h4, h5, h6 { margin-top: 0; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; }
        p { margin-top: 0; margin-bottom: 1rem; }
        label { display: inline-block; margin-bottom: 0.5rem; }
        button, input, textarea { margin: 0; font-family: inherit; font-size: inherit; line-height: inherit; }
        button { cursor: pointer; }

        /* === Bootstrap Grid === */
        .container { width: 100%; padding-right: 0.75rem; padding-left: 0.75rem; margin-right: auto; margin-left: auto; }
        @media (min-width: 576px) { .container { max-width: 540px; } }
        @media (min-width: 768px) { .container { max-width: 720px; } }
        @media (min-width: 992px) { .container { max-width: 960px; } }
        @media (min-width: 1200px) { .container { max-width: 1140px; } }
        .row { display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem; }
        .row > * { flex-shrink: 0; width: 100%; max-width: 100%; padding-right: 0.75rem; padding-left: 0.75rem; }
        @media (min-width: 992px) {
            .col-lg-6 { flex: 0 0 auto; width: 50%; }
            .col-lg-5 { flex: 0 0 auto; width: 41.666667%; }
            .gx-lg-5 > * { padding-right: 1.5rem; padding-left: 1.5rem; }
            .text-lg-start { text-align: left !important; }
            .mb-lg-0 { margin-bottom: 0 !important; }
        }

        /* === Bootstrap Utilities === */
        .text-center { text-align: center; }
        .text-muted { color: #6c757d !important; }
        .fw-bold { font-weight: 700 !important; }
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: 0.25rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mb-5 { margin-bottom: 3rem !important; }
        .mt-2 { margin-top: 0.5rem !important; }
        .mt-3 { margin-top: 1rem !important; }
        .my-5 { margin-top: 3rem !important; margin-bottom: 3rem !important; }
        .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
        .p-8 { padding: 2rem !important; }
        @media (min-width: 768px) { .px-md-5 { padding-left: 3rem !important; padding-right: 3rem !important; } }
        .w-100 { width: 100% !important; }
        .d-block { display: block !important; }
        .position-absolute { position: absolute !important; }
        .position-relative { position: relative !important; }
        .rounded-circle { border-radius: 50% !important; }
        .overflow-hidden { overflow: hidden !important; }
        .align-items-center { align-items: center !important; }
        .display-5 { font-size: calc(1.425rem + 2.1vw); font-weight: 300; line-height: 1.2; }
        .ls-tight { letter-spacing: -0.02em; }

        /* === Bootstrap Card === */
        .card { position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.75rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15); }
        .card-body { flex: 1 1 auto; padding: 1.5rem; }
        @media (min-width: 768px) { .card-body.px-md-5 { padding-left: 3rem !important; padding-right: 3rem !important; } }

        /* === Bootstrap Forms === */
        .form-label { margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #00473e; }
        .form-control { display: block; width: 100%; padding: 0.625rem 0.75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff; border: 2px solid #e9ecef; border-radius: 0.375rem; transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; appearance: none; outline: none; }
        .form-control:focus { border-color: #faae2b; box-shadow: 0 0 0 0.2rem rgba(250,174,43,0.2); }
        .form-control::placeholder { color: #6c757d; opacity: 1; }
        .input-group { position: relative; display: flex; flex-wrap: wrap; align-items: stretch; width: 100%; }
        .input-group > .form-control { position: relative; flex: 1 1 auto; width: 1%; min-width: 0; }
        .input-group > .form-control:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
        .input-group-text { display: flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #00473e; text-align: center; white-space: nowrap; background-color: #f2f7f5; border: 2px solid #e9ecef; border-right: none; border-radius: 0.375rem 0 0 0.375rem; }

        /* === Bootstrap Buttons === */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 400; line-height: 1.5; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; user-select: none; border: 1px solid transparent; padding: 0.625rem 1.25rem; font-size: 1rem; border-radius: 0.375rem; transition: all 0.15s ease-in-out; }
        .btn-outline-secondary { color: #6c757d; border-color: #6c757d; background-color: transparent; }
        .btn-outline-secondary:hover { color: #fff; background-color: #6c757d; }
        .btn-outline-warning { color: #faae2b; border-color: #faae2b; background-color: transparent; }
        .btn-outline-warning:hover { color: #000; background-color: #faae2b; }

        /* === Bootstrap Alert === */
        .alert { position: relative; padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.375rem; }
        .alert-danger { color: #842029; background-color: #f8d7da; border-color: #f5c2c7; }
        .alert-warning { color: #664d03; background-color: #fff3cd; border-color: #ffecb5; }
        .btn-close { box-sizing: content-box; width: 1em; height: 1em; padding: 0.25em; color: #000; background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat; border: 0; border-radius: 0.375rem; opacity: .5; cursor: pointer; position: absolute; top: 0.75rem; right: 0.75rem; }

        /* === Bootstrap Icons Fallback (inline SVG via CSS) === */
        .bi { display: inline-block; width: 1em; height: 1em; vertical-align: -0.125em; background-repeat: no-repeat; background-position: center; background-size: contain; }
        .bi::before { content: none !important; }
        .bi-envelope { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z'/%3E%3C/svg%3E"); }
        .bi-lock { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1'/%3E%3C/svg%3E"); }
        .bi-eye { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z'/%3E%3Cpath d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0'/%3E%3C/svg%3E"); }
        .bi-eye-slash { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z'/%3E%3Cpath d='M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829'/%3E%3Cpath d='M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z'/%3E%3C/svg%3E"); }
        .bi-box-arrow-in-right { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z'/%3E%3Cpath fill-rule='evenodd' d='M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z'/%3E%3C/svg%3E"); }
        .bi-shield-check, .bi-shield-check-fill { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m2.146 5.146a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L6.5 8.793z'/%3E%3C/svg%3E"); }
        .bi-shield-lock, .bi-shield-lock-fill { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5'/%3E%3C/svg%3E"); }
        .bi-check-circle { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/%3E%3Cpath d='m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05'/%3E%3C/svg%3E"); }
        .bi-exclamation-triangle { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z'/%3E%3Cpath d='M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z'/%3E%3C/svg%3E"); }
        .bi-exclamation-circle { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/%3E%3Cpath d='M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z'/%3E%3C/svg%3E"); }
        .bi-arrow-clockwise { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z'/%3E%3Cpath d='M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466'/%3E%3C/svg%3E"); }
        .bi-arrow-left { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8'/%3E%3C/svg%3E"); }
        .bi-key, .bi-key-fill { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2'/%3E%3C/svg%3E"); }
        .bi-person-plus { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z'/%3E%3Cpath fill-rule='evenodd' d='M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5'/%3E%3C/svg%3E"); }
        .bi-clock-history { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.253.556-.574 1.073zm-1.543 1.872q.36-.37.664-.79l.814.59q-.362.49-.79.922zm-1.81 1.27q.44-.257.838-.576l.633.748q-.474.4-1.002.727zM8.5 15.1a7 7 0 0 0 1.658-.44l.364.94q-.61.237-1.26.393zm-1 0q-.258.044-.521.07l-.124-.988q.217-.029.44-.07zM6.197 1.045l-.13-.99a8 8 0 0 1 1.17-.114l.015.994a7 7 0 0 0-1.055.11m-1.59.39q-.354.149-.69.332L3.353.596a8 8 0 0 1 1.58-.59zm-1.67.975q-.44.333-.836.716L1.45 2.473a8 8 0 0 1 1.19-.956zM1.28 4.1Q1 4.584.78 5.1l-.935-.37a8 8 0 0 1 .49-1.103zM.205 6.048q-.066.328-.108.665l-.99-.12a8 8 0 0 1 .144-.866zM8 0a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 8 0M3.732 3.732a.5.5 0 0 1 .707 0l2.475 2.475a.5.5 0 1 1-.707.707L3.732 4.44a.5.5 0 0 1 0-.707'/%3E%3C/svg%3E"); }
        .bi-hourglass-split { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300473e' viewBox='0 0 16 16'%3E%3Cpath d='M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59s-.153-.452-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59s.153.452.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c-.18.085-.36.148-.538.195A6.8 6.8 0 0 0 4.5 9.5v1h7v-1a6.8 6.8 0 0 0-2.462-1.155 3 3 0 0 1-.538-.195'/%3E%3C/svg%3E"); }
    </style>
    <script>
        // Remove fallback CSS once Bootstrap loads successfully
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Bootstrap CSS actually loaded by testing a Bootstrap-specific computed style
            var testEl = document.createElement('div');
            testEl.className = 'visually-hidden'; // Bootstrap-only class
            testEl.style.position = 'absolute';
            document.body.appendChild(testEl);
            var cs = window.getComputedStyle(testEl);
            // Bootstrap's .visually-hidden sets clip, width:1px, height:1px, etc.
            var loaded = (cs.width === '1px' && cs.height === '1px') || (cs.overflow === 'hidden' && cs.clip !== 'auto');
            document.body.removeChild(testEl);
            if (loaded) {
                var fb = document.getElementById('bootstrap-fallback-css');
                if (fb) fb.remove();
            }
        });
    </script>

    <style>
        :root {
            --bg-color: #f2f7f5;
            --headline: #00473e;
            --paragraph: #475d5b;
            --button: #faae2b;
            --button-text: #00473e;
            --stroke: #00332c;
            --highlight: #faae2b;
            --secondary: #ffa8ba;
            --tertiary: #fa5246;
        }
        .background-radial-gradient {
            background-color: var(--bg-color);
            background-image: radial-gradient(650px circle at 0% 0%,
                rgba(0, 71, 62, 0.3) 15%,
                rgba(0, 71, 62, 0.2) 35%,
                rgba(242, 247, 245, 0.8) 75%,
                rgba(242, 247, 245, 0.9) 80%,
                transparent 100%),
              radial-gradient(1250px circle at 100% 100%,
                rgba(250, 174, 43, 0.2) 15%,
                rgba(255, 168, 186, 0.15) 35%,
                rgba(242, 247, 245, 0.8) 75%,
                rgba(242, 247, 245, 0.9) 80%,
                transparent 100%);
            min-height: 100vh;
            font-family: 'Poppins', Arial, sans-serif;
            overflow: hidden;
        }
        #radius-shape-1 {
            height: 220px;
            width: 220px;
            top: -60px;
            left: -130px;
            background: radial-gradient(var(--highlight), var(--secondary));
            overflow: hidden;
        }
        #radius-shape-2 {
            border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
            bottom: -60px;
            right: -110px;
            width: 300px;
            height: 300px;
            background: radial-gradient(var(--highlight), var(--secondary));
            overflow: hidden;
        }
        .bg-glass {
            background-color: hsla(0, 0%, 100%, 0.9) !important;
            backdrop-filter: saturate(200%) blur(25px);
        }
        .hero-text {
            color: var(--headline);
        }
        .hero-text span {
            color: var(--highlight);
        }
        .hero-description {
            color: var(--paragraph);
            opacity: 0.8;
        }
        .form-label {
            color: var(--headline);
            font-family: 'Merriweather', serif;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .form-control {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef !important;
            background-color: white !important;
        }
        .form-control:focus {
            border-color: var(--highlight) !important;
            box-shadow: 0 0 0 0.2rem var(--highlight)33, 0 0 20px rgba(250, 174, 43, 0.1);
            transform: translateY(-2px);
            background-color: white !important;
        }
        /* Validation styles for auth forms */
        form.was-validated .form-control:invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ef4444'%3e%3ccircle cx='12' cy='12' r='10'/%3e%3cpath fill='white' d='M12 14a1 1 0 0 1-1-1V8a1 1 0 1 1 2 0v5a1 1 0 0 1-1 1zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px 20px;
            padding-right: 40px;
        }
        form.was-validated .form-control:valid {
            border-color: #22c55e !important;
        }
        .input-group-text {
            background: var(--bg-color);
            color: var(--headline);
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--button) 0%, var(--highlight) 100%) !important;
            color: var(--button-text) !important;
            border: none !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(250, 174, 43, 0.3) !important;
            transition: all 0.3s ease;
            font-family: 'Merriweather', serif;
            position: relative;
            overflow: hidden;
            border-radius: 0.375rem !important;
            padding: 0.75rem 1.5rem !important;
            text-align: center !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, var(--highlight) 0%, var(--secondary) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(250, 174, 43, 0.4);
            transform: translateY(-2px);
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0 0.5rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1.5px solid #e0e0e0;
        }
        .divider:not(:empty)::before {
            margin-right: .75em;
        }
        .divider:not(:empty)::after {
            margin-left: .75em;
        }
        .register-link {
            color: var(--headline);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .register-link:hover {
            color: var(--tertiary);
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .hero-text {
                font-size: 2rem !important;
                text-align: center;
            }
            .hero-description {
                text-align: center;
            }
            #radius-shape-1, #radius-shape-2 {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased">
    @include('components.offline-indicator')

    <section class="background-radial-gradient overflow-hidden">
        <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight hero-text">
                        Local Government Unit 1 <br />
                        <span>@yield('hero-title', 'Authentication Portal')</span>
                    </h1>
                    <p class="mb-4 hero-description">
                        @yield('hero-description', 'Secure access to LGU1 services and systems. Your gateway to efficient government services and digital infrastructure management.')
                    </p>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

    <!-- Offline Support: IndexedDB Cache + Write Queue -->
    <script src="{{ asset('js/offline-db.js') }}"></script>
    <script src="{{ asset('js/offline-queue.js') }}"></script>

    <!-- Service Worker Registration -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function(registration) {
                    console.log('[PWA] Service Worker registered, scope:', registration.scope);
                })
                .catch(function(error) {
                    console.warn('[PWA] Service Worker registration failed:', error);
                });
        });
    }
    </script>
</body>
</html>

