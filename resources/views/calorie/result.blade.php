@php $activePage = 'calorie'; @endphp
@extends('layouts.app')
@section('title', 'Hasil Analisis Kalori')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="flex items-center gap-3 mb-2">
      <a href="{{ route('calorie.index') }}" class="text-textmuted hover:text-accent transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-xl font-bold text-textmain">Hasil Analisis</h1>
        <p class="text-xs text-textmuted font-medium">{{ $meal->tanggal->translatedFormat('l, d F Y') }} •
          {{ $meal->created_at->format('H:i') }}
        </p>
      </div>
    </header>

    {{-- Photo --}}
    @if($meal->foto_makanan)
      <div class="rounded-2xl overflow-hidden border border-chartbg/50 shadow-sm">
        <img src="{{ asset('storage/' . $meal->foto_makanan) }}" alt="{{ $meal->nama_makanan }}"
          class="w-full h-48 object-cover">
      </div>
    @endif

    {{-- Food Info --}}
    <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h2 class="text-lg font-bold text-textmain">{{ $meal->nama_makanan }}</h2>
          <p class="text-xs text-textmuted font-medium">{{ $meal->metode_masak }} • {{ ucfirst($meal->tipe_input) }}</p>
        </div>
        <span
          class="bg-pastelorange text-accent text-xs font-bold px-3 py-1 rounded-full">{{ $meal->tipe_input === 'foto' ? 'Foto' : ' Manual' }}</span>
      </div>

      {{-- Calorie Hero --}}
      <div class="bg-accent rounded-2xl p-6 text-center text-white mb-5">
        <p class="text-sm font-medium opacity-80 mb-1">Total Kalori</p>
        <p class="text-5xl font-black">{{ number_format($meal->total_kalori, 0) }}</p>
        <p class="text-sm font-bold opacity-80">kcal</p>
      </div>

      {{-- Macro Nutrients --}}
      <div class="grid grid-cols-4 gap-3 mb-5">
        <div class="bg-appbg rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($meal->protein, 1) }}</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Protein (g)</p>
        </div>
        <div class="bg-appbg rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($meal->karbohidrat, 1) }}</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Karbo (g)</p>
        </div>
        <div class="bg-appbg rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($meal->lemak, 1) }}</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Lemak (g)</p>
        </div>
        <div class="bg-appbg rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($meal->serat, 1) }}</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Serat (g)</p>
        </div>
      </div>

      {{-- Macro Bar Chart --}}
      @php
        $maxMacro = max($meal->protein ?? 0, $meal->karbohidrat ?? 0, $meal->lemak ?? 0, 1);
      @endphp
      <div class="space-y-2 mb-5">
        <div>
          <div class="flex justify-between text-xs mb-1">
            <span class="font-medium text-textmain">Protein</span>
            <span class="font-bold text-accent">{{ number_format($meal->protein, 1) }}g</span>
          </div>
          <div class="w-full bg-chartbg/50 rounded-full h-2">
            <div class="bg-blue-400 h-2 rounded-full transition-all"
              style="width: {{ ($meal->protein / $maxMacro) * 100 }}%"></div>
          </div>
        </div>
        <div>
          <div class="flex justify-between text-xs mb-1">
            <span class="font-medium text-textmain">Karbohidrat</span>
            <span class="font-bold text-accent">{{ number_format($meal->karbohidrat, 1) }}g</span>
          </div>
          <div class="w-full bg-chartbg/50 rounded-full h-2">
            <div class="bg-yellow-400 h-2 rounded-full transition-all"
              style="width: {{ ($meal->karbohidrat / $maxMacro) * 100 }}%"></div>
          </div>
        </div>
        <div>
          <div class="flex justify-between text-xs mb-1">
            <span class="font-medium text-textmain">Lemak</span>
            <span class="font-bold text-accent">{{ number_format($meal->lemak, 1) }}g</span>
          </div>
          <div class="w-full bg-chartbg/50 rounded-full h-2">
            <div class="bg-red-400 h-2 rounded-full transition-all"
              style="width: {{ ($meal->lemak / $maxMacro) * 100 }}%"></div>
          </div>
        </div>
      </div>

      {{-- Bahan-bahan --}}
      @if($meal->bahan_bahan && count($meal->bahan_bahan) > 0)
        <div class="mb-5">
          <h3 class="text-xs font-bold text-textmuted uppercase tracking-wider mb-2">Bahan-bahan</h3>
          <div class="flex flex-wrap gap-2">
            @foreach($meal->bahan_bahan as $bahan)
              <span class="bg-pastelorange/50 text-accent text-xs font-bold px-3 py-1.5 rounded-full">
                {{ $bahan['nama'] }}: {{ $bahan['jumlah'] }} {{ $bahan['satuan'] }}
              </span>
            @endforeach
          </div>
        </div>
      @endif

      {{-- AI Explanation --}}
      @if($meal->ai_explanation)
        <div class="bg-appbg rounded-xl p-4 border border-chartbg/50">
          <h3 class="text-xs font-bold text-textmuted uppercase tracking-wider mb-2 flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
              </path>
            </svg>
            Penjelasan AI
          </h3>
          <p class="text-sm text-textmain leading-relaxed">{{ $meal->ai_explanation }}</p>
        </div>
      @endif
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
      <a href="{{ route('calorie.index') }}"
        class="flex-1 bg-accent hover:bg-accenthover text-white text-sm font-bold py-3.5 rounded-xl transition-all flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali
      </a>
      <form action="{{ route('calorie.destroy', $meal->id) }}" method="POST" onsubmit="return confirm('Hapus log ini?')"
        class="flex-1">
        @csrf @method('DELETE')
        <button type="submit"
          class="w-full bg-dangerbg hover:bg-danger text-danger hover:text-white text-sm font-bold py-3.5 rounded-xl transition-all flex items-center justify-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
            </path>
          </svg>
          Hapus
        </button>
      </form>
    </div>
  </div>
@endsection