<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden">
        {{ $slot }}
        <script>
          (function(){
            try {
              const candidates = document.querySelectorAll('main table, table.min-w-full, table.table-auto, body > table');
              candidates.forEach(function(tbl){
                if (tbl.closest('.responsive-scroll, .overflow-x-auto')) return;
                const wrap = document.createElement('div');
                wrap.className = 'overflow-x-auto responsive-scroll';
                tbl.parentNode.insertBefore(wrap, tbl);
                wrap.appendChild(tbl);
              });
            } catch(e) {}
          })();
        </script>
    </body>
</html>
