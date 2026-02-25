<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Ayo Sehat - Login</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#f97316">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { appbg: '#f8fafc', cardbg: '#ffffff', accent: '#f97316', accenthover: '#ea580c', textmain: '#0f172a', textmuted: '#64748b', chartbg: '#e2e8f0', pastelorange: '#ffedd5', pastelpeach: '#fed7aa' }, fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'] } } } }
  </script>
</head>

<body
  class="bg-appbg text-textmain font-sans min-h-screen flex items-center justify-center p-5 relative overflow-hidden">
  <div
    class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-pastelpeach/50 rounded-full mix-blend-multiply filter blur-3xl opacity-60 z-0 pointer-events-none">
  </div>
  <div
    class="absolute bottom-[-10%] right-[-5%] w-72 h-72 bg-pastelorange/70 rounded-full mix-blend-multiply filter blur-3xl opacity-60 z-0 pointer-events-none">
  </div>

  <div class="w-full max-w-md bg-cardbg rounded-3xl shadow-sm border border-chartbg/50 p-8 sm:p-10 relative z-10">
    <div class="text-center mb-8">
      <div class="w-32 h-32 mx-auto mb-5 flex items-center justify-center">
        <img src="{{ asset('images/hello.png') }}" alt="Welcome" onerror="this.style.display='none'">
      </div>
      <h1 class="text-2xl font-bold text-textmain tracking-tight mb-2">Selamat Datang</h1>
      <p class="text-sm font-medium text-textmuted">Masuk untuk mencatat progres hari ini.</p>
    </div>

    @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-4 text-sm">
        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
      @csrf
      <div>
        <label for="email" class="block text-xs font-bold text-textmuted mb-2 uppercase tracking-wider">Email
          Akun</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
          placeholder="ex: heru@student.amikom.ac.id"
          class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3.5 text-textmain font-semibold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal"
          required>
      </div>
      <div>
        <label for="password" class="block text-xs font-bold text-textmuted mb-2 uppercase tracking-wider">Kata
          Sandi</label>
        <input type="password" id="password" name="password" placeholder="••••••••"
          class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3.5 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal"
          required>
      </div>
      <div class="pt-4">
        <button type="submit"
          class="w-full bg-accent hover:bg-accenthover text-white text-base font-bold py-3.5 rounded-xl shadow-[0_4px_15px_rgba(249,115,22,0.25)] transition-all flex items-center justify-center">
          Masuk ke Ayo Sehat
          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </button>
      </div>
    </form>
    <div class="mt-8 text-center border-t border-chartbg/50 pt-6">
      <p class="text-sm font-medium text-textmuted">Belum punya akun? <a href="{{ route('register') }}"
          class="text-accent font-bold hover:underline">Daftar sekarang</a></p>
    </div>
  </div>
</body>

</html>