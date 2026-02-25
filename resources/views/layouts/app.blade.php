<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Ayo Sehat - @yield('title', 'Dashboard')</title>

  {{-- PWA Meta Tags --}}
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#f97316">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Ayo Sehat">
  <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
  <meta name="msapplication-TileImage" content="/icons/icon-144x144.png">
  <meta name="msapplication-TileColor" content="#f97316">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            appbg: '#f8fafc',
            cardbg: '#ffffff',
            accent: '#f97316',
            accenthover: '#ea580c',
            textmain: '#0f172a',
            textmuted: '#64748b',
            chartbg: '#e2e8f0',
            pastelorange: '#ffedd5',
            pastelpeach: '#fed7aa',
            pastelyellow: '#fef08a',
            pastelrose: '#ffe4e6',
            danger: '#ef4444',
            dangerbg: '#fef2f2',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type="number"] {
      -moz-appearance: textfield;
    }
  </style>
</head>

<body class="bg-gray-100 text-textmain font-sans min-h-screen flex">

  @include('partials.mobile-nav')
  @include('partials.sidebar')

  <main class="w-full min-h-screen bg-appbg relative pb-28 md:pb-10 md:ml-64 p-5 md:p-8 lg:p-10">
    @if(session('success'))
      <div class="max-w-3xl mx-auto mb-4">
        <div
          class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 text-sm font-medium flex items-center justify-between">
          <span>{{ session('success') }}</span>
          <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
        </div>
      </div>
    @endif

    @if($errors->any())
      <div class="max-w-3xl mx-auto mb-4">
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 text-sm font-medium">
          <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    @yield('content')
  </main>

  {{-- Service Worker Registration --}}
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
          .then((registration) => {
            console.log('[PWA] Service Worker registered:', registration.scope);
          })
          .catch((error) => {
            console.log('[PWA] Service Worker registration failed:', error);
          });
      });
    }
  </script>
</body>

</html>