<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light only">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    <title inertia>{{ config('app.name', 'Lost City Market') }}</title>

    @vite(["resources/scripts/app.ts", "resources/views/pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased selection:bg-brand bg-black text-white">
    @inertia
</body>

</html>