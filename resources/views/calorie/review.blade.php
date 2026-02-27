@php $activePage = 'calorie'; @endphp
@extends('layouts.app')
@section('title', 'Review Makanan')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="flex items-center gap-3 mb-2">
      <a href="{{ route('calorie.index') }}" class="text-textmuted hover:text-accent transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-xl font-bold text-textmain"> Review Data Makanan</h1>
        <p class="text-xs text-textmuted font-medium">Periksa & edit sebelum analisis kalori</p>
      </div>
    </header>

    @if(session('error'))
      <div
        class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm font-medium flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
      </div>
    @endif

    {{-- Foto Preview --}}
    @if($meal->foto_makanan)
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 overflow-hidden shadow-sm">
        <img src="{{ asset('storage/' . $meal->foto_makanan) }}" alt="{{ $meal->nama_makanan }}"
          class="w-full h-48 object-cover">
        <div class="p-3 bg-pastelorange/30 flex items-center gap-2">
          <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="text-xs text-accent font-medium">Data di bawah dideteksi oleh AI. Silakan periksa & edit jika
            perlu.</span>
        </div>
      </div>
    @endif

    <form action="{{ route('calorie.updateReview', $meal->id) }}" method="POST" id="reviewForm">
      @csrf

      {{-- Info Makanan --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm space-y-5">
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider flex items-center">
          <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
            </path>
          </svg>
          Info Makanan
        </h2>
        <div>
          <label for="nama_makanan" class="block text-xs font-bold text-textmuted mb-2">Nama Makanan</label>
          <input type="text" id="nama_makanan" name="nama_makanan" value="{{ old('nama_makanan', $meal->nama_makanan) }}"
            required
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-semibold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
        </div>
        <div>
          <label for="metode_masak" class="block text-xs font-bold text-textmuted mb-2">Metode Masak</label>
          <input type="text" id="metode_masak" name="metode_masak" value="{{ old('metode_masak', $meal->metode_masak) }}"
            required
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-semibold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
        </div>
      </div>

      {{-- Bahan-bahan --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm mt-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider flex items-center">
            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
              </path>
            </svg>
            Bahan-bahan
          </h2>
          <button type="button" onclick="addBahan()"
            class="text-accent bg-pastelorange hover:bg-pastelpeach text-xs font-bold px-3 py-1.5 rounded-xl transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah
          </button>
        </div>

        <div id="bahan-list" class="space-y-3">
          @foreach($meal->bahan_bahan ?? [] as $i => $bahan)
            <div class="bahan-item flex gap-2 items-start">
              <div class="flex-1">
                <input type="text" name="bahan[{{ $i }}][nama]" value="{{ $bahan['nama'] ?? '' }}" placeholder="Nama bahan"
                  required
                  class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
              </div>
              <div class="w-20">
                <input type="text" name="bahan[{{ $i }}][jumlah]" value="{{ $bahan['jumlah'] ?? '' }}" placeholder="100"
                  required
                  class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal text-center">
              </div>
              <div class="w-24">
                <input type="text" name="bahan[{{ $i }}][satuan]" value="{{ $bahan['satuan'] ?? 'gram' }}"
                  placeholder="gram" required
                  class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
              </div>
              <button type="button" onclick="removeBahan(this)"
                class="w-9 h-9 mt-0.5 rounded-lg bg-dangerbg text-danger hover:bg-danger hover:text-white flex items-center justify-center transition flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="flex gap-3 mt-6">
        <button type="submit" name="action" value="save"
          class="flex-1 bg-chartbg/60 hover:bg-chartbg text-textmain text-sm font-bold py-3.5 rounded-xl transition-all flex items-center justify-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
          </svg>
          Simpan Perubahan
        </button>
      </div>
    </form>

    {{-- Analyze Button (separate form) --}}
    <form action="{{ route('calorie.analyze', $meal->id) }}" method="POST" id="analyzeForm">
      @csrf
      <button type="submit" id="analyzeBtn"
        class="w-full bg-accent hover:bg-accenthover text-white text-base font-bold py-4 rounded-xl shadow-[0_4px_15px_rgba(249,115,22,0.3)] transition-all flex items-center justify-center">
        <svg class="w-5 h-5 mr-2 animate-spin hidden" id="spinnerIcon" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <svg class="w-5 h-5 mr-2" id="normalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <span id="btnText"> Analisis Kalori dengan AI</span>
      </button>
    </form>
  </div>

  <script>
    let bahanIndex = {{ count($meal->bahan_bahan ?? []) }};

    function addBahan() {
      const list = document.getElementById('bahan-list');
      const html = `
              <div class="bahan-item flex gap-2 items-start">
                <div class="flex-1">
                  <input type="text" name="bahan[${bahanIndex}][nama]" placeholder="Nama bahan" required
                    class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
                </div>
                <div class="w-20">
                  <input type="text" name="bahan[${bahanIndex}][jumlah]" placeholder="100" required
                    class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal text-center">
                </div>
                <div class="w-24">
                  <input type="text" name="bahan[${bahanIndex}][satuan]" placeholder="gram" required
                    class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
                </div>
                <button type="button" onclick="removeBahan(this)"
                  class="w-9 h-9 mt-0.5 rounded-lg bg-dangerbg text-danger hover:bg-danger hover:text-white flex items-center justify-center transition flex-shrink-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
              </div>`;
      list.insertAdjacentHTML('beforeend', html);
      bahanIndex++;
    }

    function removeBahan(btn) {
      const items = document.querySelectorAll('.bahan-item');
      if (items.length > 1) {
        btn.closest('.bahan-item').remove();
      }
    }

    document.getElementById('analyzeForm').addEventListener('submit', function () {
      const btn = document.getElementById('analyzeBtn');
      const spinner = document.getElementById('spinnerIcon');
      const normalIcon = document.getElementById('normalIcon');
      const btnText = document.getElementById('btnText');

      btn.disabled = true;
      spinner.classList.remove('hidden');
      normalIcon.classList.add('hidden');
      btnText.textContent = 'Sedang menganalisis kalori...';
    });
  </script>
@endsection