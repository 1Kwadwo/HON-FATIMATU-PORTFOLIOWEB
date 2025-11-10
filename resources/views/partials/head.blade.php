<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $metaTitle ?? $title ?? config('app.name') }}</title>

@include('partials.seo-meta')

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|poppins:300,400,500,600,700" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<style>
    /* Fix input text visibility in dark mode */
    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="url"],
    input[type="password"],
    input[type="search"],
    input[type="tel"],
    input[type="date"],
    input[type="datetime-local"],
    input[type="file"],
    textarea,
    select {
        color: #111827 !important;
        background-color: white !important;
    }
    
    .dark input[type="text"],
    .dark input[type="email"],
    .dark input[type="number"],
    .dark input[type="url"],
    .dark input[type="password"],
    .dark input[type="search"],
    .dark input[type="tel"],
    .dark input[type="date"],
    .dark input[type="datetime-local"],
    .dark input[type="file"],
    .dark textarea,
    .dark select {
        color: #111827 !important;
        background-color: white !important;
    }
    
    /* Placeholder text */
    input::placeholder,
    textarea::placeholder {
        color: #9ca3af !important;
    }
</style>
