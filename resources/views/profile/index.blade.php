@php $activePage = 'account'; @endphp
@extends('layouts.app')
@section('title', 'Profile')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="mb-2">
      <h1 class="text-xl font-bold text-textmain">Profil Saya</h1>
      <p class="text-xs text-textmuted font-medium">Kelola informasi akun</p>
    </header>

    {{-- Profile Header --}}
    <div class="bg-gradient-to-br from-pastelorange to-pastelpeach rounded-3xl p-6 shadow-sm relative overflow-hidden">
      <img src="{{ asset('images/hello.png') }}" alt="Cat" class="absolute right-4 top-2 w-24 opacity-80"
        onerror="this.style.display='none'">
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-accent/20 rounded-2xl flex items-center justify-center">
          <span class="text-2xl font-black text-accent">{{ strtoupper(substr($user->display_name, 0, 1)) }}</span>
        </div>
        <div>
          <h2 class="text-lg font-bold text-textmain">{{ $user->display_name }}</h2>
          <p class="text-sm text-textmain/70">{{ $user->email }}</p>
        </div>
      </div>
      @if($user->tanggal_mulai_diet)
        <div class="mt-4 grid grid-cols-2 gap-3">
          <div class="bg-white/50 rounded-xl p-3">
            <p class="text-[10px] text-textmuted font-bold uppercase">Mulai Diet</p>
            <p class="text-sm font-bold text-textmain">{{ $user->tanggal_mulai_diet->translatedFormat('d M Y') }}</p>
          </div>
          <div class="bg-white/50 rounded-xl p-3">
            <p class="text-[10px] text-textmuted font-bold uppercase">Berat Awal</p>
            <p class="text-sm font-bold text-textmain">{{ $user->berat_awal }} kg</p>
          </div>
        </div>
      @endif
    </div>

    {{-- Edit Profile Form --}}
    <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm relative overflow-hidden">
      <img src="{{ asset('images/profile-cat.png') }}" alt="Cat" class="absolute right-2 bottom-2 w-20 opacity-40"
        onerror="this.style.display='none'">
      <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-6 flex items-center">
        <svg class="w-4 h-4 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Informasi Pribadi
      </h2>
      <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label for="nama" class="block text-xs font-bold text-textmuted mb-2">Nama Lengkap</label>
          <input type="text" id="nama" name="nama_lengkap"
            value="{{ old('nama_lengkap', $user->nama_lengkap ?? $user->name) }}"
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
        </div>
        <div>
          <label for="email" class="block text-xs font-bold text-textmuted mb-2">Email Akun</label>
          <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
        </div>
        <div>
          <label for="password_baru" class="block text-xs font-bold text-textmuted mb-2">Password Baru (Opsional)</label>
          <input type="password" id="password_baru" name="password_baru" placeholder="Kosongkan jika tidak ingin ganti"
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
        </div>
        <div class="flex justify-end pt-2">
          <button type="submit"
            class="bg-accent hover:bg-accenthover text-white text-sm font-bold py-3 px-6 rounded-xl transition shadow-sm">
            Perbarui Profil
          </button>
        </div>
      </form>
    </div>

    {{-- Logout --}}
    <div class="bg-cardbg rounded-2xl border border-danger/20 p-6 shadow-sm">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h3 class="text-sm font-bold text-textmain uppercase tracking-wider mb-1 flex items-center">
            <svg class="w-4 h-4 mr-2 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Keluar Akun
          </h3>
          <p class="text-xs text-textmuted font-medium">Kamu akan keluar dari sesi saat ini.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit"
            class="bg-dangerbg hover:bg-danger text-danger hover:text-white text-sm font-bold py-3 px-6 rounded-xl transition shadow-sm border border-danger/20 hover:border-danger flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection