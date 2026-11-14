@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'ogTitle' => null,
    'ogDescription' => null,
])
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#6818E8">

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

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png?v=4') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png?v=4') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png?v=4') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest?v=4') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100..900;1,100..900&family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <x-navbar />

    {{ $slot }}

    <x-footer />

    @stack('scripts')
</body>
</html>
