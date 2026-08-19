@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'ogTitle' => null,
    'ogDescription' => null,
])
<!doctype html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0694a2">

    <title>{{ $title ?? 'Devmatrixa – Free Developer and SEO Tools for Modern Workflows' }}</title>
    <meta name="description" content="{{ $description ?? config('seo.site_description') }}">
    <meta name="keywords" content="{{ $keywords ?? config('seo.site_keywords') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Devmatrixa">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'Devmatrixa') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? config('seo.site_description')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(config('seo.og_image')) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle ?? ($title ?? 'Devmatrixa') }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? ($description ?? config('seo.site_description')) }}">

    <link rel="icon" href="{{ asset('favicon.ico?v=2') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="text-slate-100">
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 left-[-4rem] h-72 w-72 rounded-full bg-teal-300/30 blur-3xl"></div>
        <div class="absolute right-[-3rem] top-1/3 h-80 w-80 rounded-full bg-lime-300/25 blur-3xl"></div>
        <div class="absolute bottom-[-5rem] left-1/3 h-96 w-96 rounded-full bg-cyan-300/20 blur-3xl"></div>
    </div>

    <x-navbar />

    {{ $slot }}

    <x-footer />

    @stack('scripts')
</body>
</html>
