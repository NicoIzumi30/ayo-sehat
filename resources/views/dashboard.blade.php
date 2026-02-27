@php $activePage = 'home'; @endphp
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    {{-- Greeting --}}
    <div class="bg-gradient-to-br from-pastelorange to-pastelpeach rounded-3xl p-6 shadow-sm relative overflow-hidden">
      <img src="{{ asset('images/hello.png') }}" alt="Cat" class="absolute right-2 top-2 w-28 h-28 opacity-90"
        onerror="this.style.display='none'">
      <h1 class="text-2xl font-bold text-textmain mb-1">Halo, {{ $user->display_name }}!</h1>
      <p class="text-sm font-medium text-textmain/70">Siap untuk mencetak rekor baru hari ini?</p>
    </div>

    {{-- Overview --}}
    <div>
      <h2 class="text-lg font-bold text-textmain mb-1">Overview</h2>
      <p class="text-xs text-textmuted font-medium mb-4">{{ $today->translatedFormat('l, d M') }} • Progress Hari Ini</p>
    </div>

    {{-- Daily Steps --}}
    <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm relative overflow-hidden">
      <img src="{{ asset('images/walking.png') }}" alt="Cat Run" class="absolute right-4 bottom-4 w-32 opacity-80"
        onerror="this.style.display='none'">
      <h3 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-2">Daily Steps</h3>
      <div class="flex items-baseline gap-1">
        <span class="text-4xl font-black text-textmain">{{ number_format($todayRecord->langkah_kaki ?? 0) }}</span>
        @php $targetLangkah = $target ? $target->target_langkah_harian : 10000; @endphp
        <span class="text-sm text-textmuted font-medium">/ {{ $targetLangkah / 1000 }}k lkh</span>
      </div>
      @if($streak > 0)
        <div class="mt-3 flex items-center gap-2">
          <span class="bg-pastelorange text-accent text-xs font-bold px-3 py-1 rounded-full">{{ $streak }} Day Streak</span>
          @for($i = 0; $i < min($streak, 5); $i++)
            <span class="w-2.5 h-2.5 bg-accent rounded-full"></span>
          @endfor
        </div>
      @endif
      @php $stepPercent = $target && $target->target_langkah_harian > 0 ? min(100, (($todayRecord->langkah_kaki ?? 0) / $target->target_langkah_harian) * 100) : 0; @endphp
      <div class="w-full bg-chartbg/50 rounded-full h-2 mt-4">
        <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $stepPercent }}%"></div>
      </div>
    </div>

    {{-- Weight & Waist Cards --}}
    <div class="grid grid-cols-2 gap-4">
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-5 shadow-sm relative overflow-hidden">
        <img src="{{ asset('images/bb.png') }}" alt="Apple" class="absolute right-2 bottom-2 w-16 opacity-60"
          onerror="this.style.display='none'">
        <h3 class="text-sm font-bold text-textmuted mb-1">Berat</h3>
        <p class="text-xs text-textmuted">Target: {{ $target?->target_berat ?? 80 }}kg</p>
        <p class="text-3xl font-black text-textmain mt-2">{{ $todayRecord->berat_badan ?? '-' }} <span
            class="text-sm font-medium text-textmuted">kg</span></p>
        @php $beratPct = $todayRecord && $todayRecord->berat_badan && $user->berat_awal && $target ? min(100, max(0, (($user->berat_awal - $todayRecord->berat_badan) / max(1, $user->berat_awal - $target->target_berat)) * 100)) : 0; @endphp
        <div class="w-full bg-chartbg/50 rounded-full h-1.5 mt-3">
          <div class="bg-accent h-1.5 rounded-full" style="width: {{ $beratPct }}%"></div>
        </div>
      </div>
      <div
        class="bg-gradient-to-br from-pastelyellow/50 to-pastelorange/30 rounded-2xl border border-chartbg/50 p-5 shadow-sm relative overflow-hidden">
        <img src="{{ asset('images/lp.png') }}" alt="Orange" class="absolute right-2 bottom-2 w-16 opacity-60"
          onerror="this.style.display='none'">
        <h3 class="text-sm font-bold text-textmuted mb-1">Pinggang</h3>
        <p class="text-xs text-textmuted">Target: {{ $target?->target_lingkar_pinggang ?? 85 }}cm</p>
        <p class="text-3xl font-black text-textmain mt-2">{{ $todayRecord->lingkar_pinggang ?? '-' }} <span
            class="text-sm font-medium text-textmuted">cm</span></p>
      </div>
    </div>

    {{-- Calorie Intake --}}
    <a href="{{ route('calorie.index') }}"
      class="block bg-gradient-to-br from-accent/10 to-pastelorange/40 rounded-2xl border border-accent/20 p-5 shadow-sm hover:shadow-md transition-all group">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-1">🔥 Asupan Kalori</h3>
          <div class="flex items-baseline gap-1">
            <span class="text-3xl font-black text-textmain">{{ number_format($todayCalories, 0) }}</span>
            <span class="text-sm text-textmuted font-medium">kcal</span>
          </div>
          <p class="text-xs text-textmuted mt-1">{{ $todayMealCount }} makanan dicatat hari ini</p>
        </div>
        <div
          class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
          </svg>
        </div>
      </div>
    </a>

    {{-- Body Photo --}}
    @if($todayRecord && $todayRecord->foto_badan)
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-3">📸 Foto Badan Hari Ini</h3>
        <div class="rounded-xl overflow-hidden border border-chartbg/80 relative">
          <img src="{{ asset('storage/' . $todayRecord->foto_badan) }}" alt="Foto Badan"
            class="w-full h-auto object-cover max-h-64">
        </div>
      </div>
    @endif

    {{-- Diet Habits --}}
    <div
      class="bg-gradient-to-br from-pastelrose/50 to-pastelorange/20 rounded-2xl border border-chartbg/50 p-6 shadow-sm relative overflow-hidden">
      <img src="{{ asset('images/2.png') }}" alt="Pig" class="absolute right-2 bottom-2 w-20 opacity-60"
        onerror="this.style.display='none'">
      <h3 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-1">Diet Habit</h3>
      <p class="text-xs text-textmuted font-medium mb-3">Konsistensi</p>
      <div class="flex items-baseline gap-2 mb-3">
        <span
          class="text-3xl font-black {{ $habitsTotal > 0 && $habitsChecked / max(1, $habitsTotal) >= 0.8 ? 'text-green-600' : 'text-accent' }}">{{ $habitsChecked }}/{{ $habitsTotal }}</span>
        <span class="text-xs text-textmuted font-medium">habit tercapai</span>
      </div>
      @if($habitsToday->count() > 0)
        <div class="space-y-1.5">
          @foreach($habitsToday as $h)
            <div class="flex items-center gap-2 text-sm">
              @if($h->is_checked)
                <span class="w-5 h-5 bg-accent rounded-full flex items-center justify-center"><svg class="w-3 h-3 text-white"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                  </svg></span>
                <span class="text-textmain font-medium">{{ $h->habitMaster->nama_habit }}</span>
              @else
                <span class="w-5 h-5 border-2 border-chartbg rounded-full"></span>
                <span class="text-textmuted line-through">{{ $h->habitMaster->nama_habit }}</span>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <p class="text-sm text-textmuted italic">Belum ada data hari ini. <a href="{{ route('record.create') }}"
            class="text-accent font-bold hover:underline">Catat sekarang!</a></p>
      @endif
    </div>

    {{-- Workout Progress --}}
    <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
      <h3 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
        </svg>
        Progress Olahraga
      </h3>
      @if($targetWorkouts->count() > 0)
        <div class="space-y-3">
          @foreach($targetWorkouts as $tw)
            @php
              $actual = $workoutsToday->firstWhere('target_olahraga_id', $tw->id);
              $val = $actual ? $actual->value : 0;
              $pct = $tw->target_value > 0 ? min(100, ($val / $tw->target_value) * 100) : 0;
            @endphp
            <div>
              <div class="flex justify-between text-xs mb-1">
                <span class="font-bold text-textmain">{{ $tw->nama_olahraga }}</span>
                <span class="font-medium text-textmuted">{{ $val }}/{{ $tw->target_value }} {{ $tw->satuan }}</span>
              </div>
              <div class="w-full bg-chartbg/50 rounded-full h-2">
                <div class="bg-accent h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="text-sm text-textmuted italic">Belum ada target olahraga.</p>
      @endif
    </div>

    {{-- Weight Trend --}}
    @if($weightTrend->count() > 1)
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-4">📈 Trend Berat Badan</h3>
        <div class="flex items-end gap-2 h-32">
          @php
            $minW = $weightTrend->min('berat_badan');
            $maxW = $weightTrend->max('berat_badan');
            $range = max(1, $maxW - $minW);
          @endphp
          @foreach($weightTrend as $wr)
            @php $barH = max(10, (($wr->berat_badan - $minW) / $range) * 100); @endphp
            <div class="flex-1 flex flex-col items-center">
              <span class="text-[10px] font-bold text-textmuted mb-1">{{ $wr->berat_badan }}</span>
              <div class="w-full bg-accent/80 rounded-t-lg" style="height: {{ $barH }}%"></div>
              <span class="text-[9px] text-textmuted mt-1">{{ \Carbon\Carbon::parse($wr->tanggal)->format('d/m') }}</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    {{-- Quick Menu (mobile only) --}}
    <div class="md:hidden">
      <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-3">Menu Lainnya</h2>
      <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('target') }}"
          class="bg-cardbg rounded-2xl border border-chartbg/50 p-4 shadow-sm flex items-center gap-3 hover:border-accent/30 transition-all">
          <div class="w-10 h-10 bg-pastelyellow/50 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-bold text-textmain">Targets</p>
            <p class="text-[10px] text-textmuted">Atur target diet</p>
          </div>
        </a>
        <a href="{{ route('profile') }}"
          class="bg-cardbg rounded-2xl border border-chartbg/50 p-4 shadow-sm flex items-center gap-3 hover:border-accent/30 transition-all">
          <div class="w-10 h-10 bg-pastelrose/50 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
              </path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-bold text-textmain">Pengaturan</p>
            <p class="text-[10px] text-textmuted">Profil & akun</p>
          </div>
        </a>
      </div>
    </div>
  </div>
@endsection