@php $activePage = 'calorie'; @endphp
@extends('layouts.app')
@section('title', 'Analisis Kalori')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="mb-2">
      <h1 class="text-xl font-bold text-textmain"> Analisis Kalori</h1>
      <p class="text-xs text-textmuted font-medium">{{ $today->translatedFormat('l, d F Y') }}</p>
    </header>

    {{-- Today Summary --}}
    <div class="bg-gradient-to-br from-pastelorange to-pastelpeach/70 rounded-2xl p-6 shadow-sm">
      <h2 class="text-sm font-bold text-textmain/70 uppercase tracking-wider mb-3">Asupan Hari Ini</h2>
      <div class="text-center mb-4">
        <span class="text-5xl font-black text-textmain">{{ number_format($totalKalori, 0) }}</span>
        <span class="text-lg font-bold text-textmain/60 ml-1">kcal</span>
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($totalProtein, 1) }}g</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Protein</p>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($totalKarbo, 1) }}g</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Karbo</p>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
          <p class="text-lg font-black text-textmain">{{ number_format($totalLemak, 1) }}g</p>
          <p class="text-[10px] font-bold text-textmuted uppercase">Lemak</p>
        </div>
      </div>
    </div>

    {{-- Action Buttons --}}
    <div class="grid grid-cols-2 gap-4">
      <a href="{{ route('calorie.photo') }}"
        class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm hover:shadow-md hover:border-accent/30 transition-all group text-center">
        <div
          class="w-14 h-14 bg-pastelorange rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
          <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
          </svg>
        </div>
        <h3 class="text-sm font-bold text-textmain mb-1">Scan Foto</h3>
        <p class="text-[11px] text-textmuted">Upload foto & AI analisis otomatis</p>
      </a>

      <a href="{{ route('calorie.manual') }}"
        class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm hover:shadow-md hover:border-accent/30 transition-all group text-center">
        <div
          class="w-14 h-14 bg-pastelrose rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
          <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
            </path>
          </svg>
        </div>
        <h3 class="text-sm font-bold text-textmain mb-1">Input Manual</h3>
        <p class="text-[11px] text-textmuted">Ketik detail makanan sendiri</p>
      </a>
    </div>

    {{-- Today Meal Logs --}}
    @if($todayMeals->count() > 0)
      <div>
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-3">Log Makanan Hari Ini</h2>
        <div class="space-y-3">
          @foreach($todayMeals as $meal)
            <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-4 shadow-sm flex items-center gap-4">
              @if($meal->foto_makanan)
                <img src="{{ asset('storage/' . $meal->foto_makanan) }}" alt="{{ $meal->nama_makanan }}"
                  class="w-16 h-16 rounded-xl object-cover border border-chartbg/50">
              @else
                <div class="w-16 h-16 rounded-xl bg-pastelorange/50 flex items-center justify-center text-2xl">🍽️</div>
              @endif
              <div class="flex-1 min-w-0">
                <h3 class="text-sm font-bold text-textmain truncate">{{ $meal->nama_makanan }}</h3>
                <p class="text-xs text-textmuted">{{ $meal->metode_masak }} • {{ $meal->created_at->format('H:i') }}</p>
                <div class="flex gap-3 mt-1">
                  <span class="text-xs font-bold text-accent">{{ number_format($meal->total_kalori, 0) }} kcal</span>
                  <span class="text-[10px] text-textmuted">P:{{ number_format($meal->protein, 0) }}g</span>
                  <span class="text-[10px] text-textmuted">K:{{ number_format($meal->karbohidrat, 0) }}g</span>
                  <span class="text-[10px] text-textmuted">L:{{ number_format($meal->lemak, 0) }}g</span>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <a href="{{ route('calorie.result', $meal->id) }}" class="text-accent hover:text-accenthover transition"
                  title="Lihat Detail">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                  </svg>
                </a>
                <form action="{{ route('calorie.destroy', $meal->id) }}" method="POST"
                  onsubmit="return confirm('Hapus log ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                      </path>
                    </svg>
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @else
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-8 shadow-sm text-center">
        <p class="text-4xl mb-3">🍽️</p>
        <p class="text-textmuted font-medium mb-1">Belum ada log makanan hari ini</p>
        <p class="text-xs text-textmuted">Mulai catat makananmu untuk melacak asupan kalori!</p>
      </div>
    @endif

  </div>
@endsection