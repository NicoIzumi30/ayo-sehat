@php $activePage = 'targets'; @endphp
@extends('layouts.app')
@section('title', 'Targets')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="flex items-center justify-between mb-2">
      <div>
        <h1 class="text-xl font-bold text-textmain">Goals & Targets</h1>
        <p class="text-xs text-textmuted font-medium">Atur target harian dan mingguan</p>
      </div>
    </header>

    <form action="{{ route('target.update') }}" method="POST" class="space-y-6">
      @csrf

      {{-- Body Targets --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm relative overflow-hidden">
        <img src="{{ asset('images/target-cat.png') }}" alt="Cat" class="absolute right-2 bottom-2 w-24 opacity-50"
          onerror="this.style.display='none'">
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-6 flex items-center">
          <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
          </svg>
          Target Metrik Tubuh
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div>
            <label for="target_berat" class="block text-xs font-bold text-textmuted mb-2">Berat Badan (kg)</label>
            <input type="number" id="target_berat" name="target_berat" step="0.1"
              value="{{ old('target_berat', $target->target_berat ?? 80.0) }}"
              class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold text-xl text-center focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
          </div>
          <div>
            <label for="target_pinggang" class="block text-xs font-bold text-textmuted mb-2">Lingkar Pinggang (cm)</label>
            <input type="number" id="target_pinggang" name="target_lingkar_pinggang" step="0.5"
              value="{{ old('target_lingkar_pinggang', $target->target_lingkar_pinggang ?? 85.0) }}"
              class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold text-xl text-center focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
          </div>
          <div>
            <label for="target_langkah" class="block text-xs font-bold text-textmuted mb-2">Langkah Harian</label>
            <input type="number" id="target_langkah" name="target_langkah_harian"
              value="{{ old('target_langkah_harian', $target->target_langkah_harian ?? 10000) }}"
              class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold text-xl text-center focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
          </div>
        </div>

        {{-- Progress Info --}}
        @if($user->berat_awal)
          <div class="mt-6 bg-appbg rounded-xl p-4">
            <div class="flex justify-between text-xs mb-2">
              <span class="font-bold text-textmuted">Berat Awal</span>
              <span class="font-black text-textmain">{{ $user->berat_awal }} kg</span>
            </div>
            <div class="flex justify-between text-xs">
              <span class="font-bold text-textmuted">Mulai Diet</span>
              <span
                class="font-black text-textmain">{{ $user->tanggal_mulai_diet?->translatedFormat('d F Y') ?? '-' }}</span>
            </div>
          </div>
        @endif
      </div>

      {{-- Workout Targets --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
            </path>
          </svg>
          Target Minimal Olahraga
        </h2>
        <div class="space-y-3">
          @foreach($workouts as $wo)
            <div class="flex items-center justify-between p-3.5 bg-appbg border border-chartbg/80 rounded-xl">
              <div>
                <span class="text-sm font-bold text-textmain">{{ $wo->nama_olahraga }}</span>
                <span class="text-xs text-textmuted ml-1">({{ $wo->satuan }})</span>
              </div>
              <div class="flex items-center gap-2">
                <button type="button" onclick="changeVal(this, -1)"
                  class="w-8 h-8 rounded-lg bg-chartbg/50 hover:bg-accent hover:text-white text-textmuted font-bold flex items-center justify-center transition">−</button>
                <input type="number" name="target_workout[{{ $wo->id }}]"
                  value="{{ old("target_workout.{$wo->id}", $wo->target_value) }}" min="0"
                  class="w-14 text-center bg-transparent text-textmain font-black text-lg focus:outline-none">
                <button type="button" onclick="changeVal(this, 1)"
                  class="w-8 h-8 rounded-lg bg-chartbg/50 hover:bg-accent hover:text-white text-textmuted font-bold flex items-center justify-center transition">+</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full bg-accent hover:bg-accenthover text-white text-base font-bold py-4 rounded-xl shadow-[0_4px_15px_rgba(249,115,22,0.3)] transition-all flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Simpan Target
      </button>
    </form>
  </div>

  <script>
    function changeVal(btn, delta) {
      const input = btn.parentElement.querySelector('input');
      let val = parseInt(input.value) || 0;
      val = Math.max(0, val + delta);
      input.value = val;
    }
  </script>
@endsection