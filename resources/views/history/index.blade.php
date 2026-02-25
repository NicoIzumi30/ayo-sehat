@php $activePage = 'diary'; @endphp
@extends('layouts.app')
@section('title', 'History Log')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="mb-2">
      <h1 class="text-xl font-bold text-textmain">History Log</h1>
      <p class="text-xs text-textmuted font-medium">{{ $currentMonth->translatedFormat('F Y') }}</p>
    </header>

    {{-- Month Navigation --}}
    <div class="flex items-center gap-2 mb-4">
      <a href="{{ route('history', ['month' => $currentMonth->copy()->subMonth()->month, 'year' => $currentMonth->copy()->subMonth()->year]) }}"
        class="bg-cardbg border border-chartbg/50 rounded-xl px-3 py-2 text-sm font-medium text-textmuted hover:text-accent transition">
        ← Sebelumnya
      </a>
      <span class="flex-1 text-center text-sm font-bold text-textmain">{{ $currentMonth->translatedFormat('F Y') }}</span>
      <a href="{{ route('history', ['month' => $currentMonth->copy()->addMonth()->month, 'year' => $currentMonth->copy()->addMonth()->year]) }}"
        class="bg-cardbg border border-chartbg/50 rounded-xl px-3 py-2 text-sm font-medium text-textmuted hover:text-accent transition">
        Berikutnya →
      </a>
    </div>

    @forelse($records as $record)
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 shadow-sm overflow-hidden" x-data="{ open: false }">
        {{-- Record Header --}}
        <div class="p-5 cursor-pointer hover:bg-gray-50/50 transition"
          onclick="this.parentElement.querySelector('.detail').classList.toggle('hidden')">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-pastelorange rounded-xl flex items-center justify-center">
                <span class="text-accent font-black text-sm">{{ $record->tanggal->format('d') }}</span>
              </div>
              <div>
                <h3 class="text-sm font-bold text-textmain">{{ $record->tanggal->translatedFormat('l, d M') }}</h3>
                <p class="text-xs text-textmuted">
                  @if($record->tanggal->isToday()) Hari Ini
                  @elseif($record->tanggal->isYesterday()) Kemarin
                  @else {{ $record->tanggal->diffForHumans() }}
                  @endif
                </p>
              </div>
            </div>
            <svg class="w-5 h-5 text-textmuted transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>

          {{-- Quick Stats --}}
          <div class="grid grid-cols-3 gap-3">
            <div class="bg-appbg rounded-xl p-3 text-center">
              <p class="text-lg font-black text-textmain">{{ $record->berat_badan ?? '-' }}</p>
              <p class="text-[10px] text-textmuted font-medium">kg</p>
            </div>
            <div class="bg-appbg rounded-xl p-3 text-center">
              <p class="text-lg font-black text-textmain">{{ number_format($record->langkah_kaki ?? 0) }}</p>
              <p class="text-[10px] text-textmuted font-medium">langkah</p>
            </div>
            <div class="bg-appbg rounded-xl p-3 text-center">
              @php $hChecked = $record->habits->where('is_checked', true)->count();
              $hTotal = $record->habits->count(); @endphp
              <p
                class="text-lg font-black {{ $hTotal > 0 && $hChecked / $hTotal >= 0.8 ? 'text-green-600' : 'text-accent' }}">
                {{ $hChecked }}/{{ $hTotal }}</p>
              <p class="text-[10px] text-textmuted font-medium">habit</p>
            </div>
          </div>
        </div>

        {{-- Expandable Detail --}}
        <div class="detail hidden border-t border-chartbg/50 p-5 bg-appbg/50 space-y-4">
          {{-- Habits --}}
          @if($record->habits->count() > 0)
            <div>
              <h4 class="text-xs font-bold text-textmuted uppercase tracking-wider mb-2">Diet Habits</h4>
              <div class="grid grid-cols-2 gap-2">
                @foreach($record->habits as $h)
                  <div class="flex items-center gap-2 text-xs">
                    @if($h->is_checked)
                      <span class="w-4 h-4 bg-accent rounded-full flex items-center justify-center"><svg
                          class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg></span>
                      <span class="font-medium text-textmain">{{ $h->habitMaster->nama_habit }}</span>
                    @else
                      <span class="w-4 h-4 border-2 border-chartbg rounded-full"></span>
                      <span class="text-textmuted line-through">{{ $h->habitMaster->nama_habit }}</span>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          {{-- Workouts --}}
          @if($record->workouts->count() > 0)
            <div>
              <h4 class="text-xs font-bold text-textmuted uppercase tracking-wider mb-2">Workout Log</h4>
              <div class="flex flex-wrap gap-2">
                @foreach($record->workouts as $w)
                  <span class="bg-pastelorange text-accent text-xs font-bold px-3 py-1.5 rounded-full">
                    {{ $w->targetOlahraga->nama_olahraga }}: {{ $w->value }} {{ $w->targetOlahraga->satuan }}
                  </span>
                @endforeach
              </div>
            </div>
          @endif

          {{-- Lingkar Pinggang --}}
          @if($record->lingkar_pinggang)
            <div class="text-xs text-textmuted">Lingkar Pinggang: <span
                class="font-bold text-textmain">{{ $record->lingkar_pinggang }} cm</span></div>
          @endif
        </div>
      </div>
    @empty
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-10 shadow-sm text-center">
        <p class="text-textmuted font-medium mb-3">Belum ada record di bulan ini.</p>
        <a href="{{ route('record.create') }}"
          class="inline-flex items-center bg-accent text-white font-bold px-6 py-3 rounded-xl hover:bg-accenthover transition">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Tambah Record Pertama
        </a>
      </div>
    @endforelse
  </div>
@endsection