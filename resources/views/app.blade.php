<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light only">

    <title inertia>{{ config('app.name', 'Lost City Market') }}</title>

    @vite(["resources/scripts/app.ts", "resources/views/pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased selection:bg-brand bg-black text-white">
    @inertia
</body>

</html>