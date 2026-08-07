<!DOCTYPE html>
<html lang="" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-200">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div class="ml-60 flex-1 flex flex-col">
            {{-- 
            <header
                class="sticky top-0 left-0 right-0 h-16 border-b border-base-300 bg-base-100 px-6 flex items-center">
                <h1 class="text-xl font-semibold">Dashboard</h1>
            </header> --}}

            <main class="bg-black px-8 py-2 flex-1">
                @yield('content')
            </main>
        </div>
    </div>
</body>


</html>
