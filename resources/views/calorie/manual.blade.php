@php $activePage = 'calorie'; @endphp
@extends('layouts.app')
@section('title', 'Input Manual Makanan')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="flex items-center gap-3 mb-2">
      <a href="{{ route('calorie.index') }}" class="text-textmuted hover:text-accent transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-xl font-bold text-textmain"> Input Manual</h1>
        <p class="text-xs text-textmuted font-medium">Masukkan detail makanan yang kamu konsumsi</p>
      </div>
    </header>

    <form action="{{ route('calorie.storeManual') }}" method="POST" id="manualForm">
      @csrf

      {{-- Info Makanan --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm space-y-5">
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider flex items-center">
          <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Info Makanan
        </h2>
        <div>
          <label for="nama_makanan" class="block text-xs font-bold text-textmuted mb-2">Nama Makanan</label>
          <input type="text" id="nama_makanan" name="nama_makanan" placeholder="ex: Nasi Goreng Kampung"
            value="{{ old('nama_makanan') }}" required
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-semibold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
        </div>
        <div>
          <label for="metode_masak" class="block text-xs font-bold text-textmuted mb-2">Metode Masak</label>
          <select id="metode_masak" name="metode_masak" required
            class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-semibold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
            <option value="">Pilih metode...</option>
            <option value="Goreng" {{ old('metode_masak') == 'Goreng' ? 'selected' : '' }}>🍳 Goreng</option>
            <option value="Rebus" {{ old('metode_masak') == 'Rebus' ? 'selected' : '' }}>🫕 Rebus</option>
            <option value="Kukus" {{ old('metode_masak') == 'Kukus' ? 'selected' : '' }}>♨️ Kukus</option>
            <option value="Panggang" {{ old('metode_masak') == 'Panggang' ? 'selected' : '' }}>🔥 Panggang</option>
            <option value="Tumis" {{ old('metode_masak') == 'Tumis' ? 'selected' : '' }}>🥘 Tumis</option>
            <option value="Mentah" {{ old('metode_masak') == 'Mentah' ? 'selected' : '' }}>🥗 Mentah/Segar</option>
            <option value="Bakar" {{ old('metode_masak') == 'Bakar' ? 'selected' : '' }}>🍖 Bakar</option>
            <option value="Lainnya" {{ old('metode_masak') == 'Lainnya' ? 'selected' : '' }}>📝 Lainnya</option>
          </select>
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
          <div class="bahan-item flex gap-2 items-start">
            <div class="flex-1">
              <input type="text" name="bahan[0][nama]" placeholder="Nama bahan (ex: Nasi)" required
                class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
            </div>
            <div class="w-20">
              <input type="text" name="bahan[0][jumlah]" placeholder="200" required
                class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal text-center">
            </div>
            <div class="w-24">
              <select name="bahan[0][satuan]" required
                class="w-full bg-appbg border border-chartbg/80 rounded-xl px-2 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
                <option value="gram">gram</option>
                <option value="ml">ml</option>
                <option value="buah">buah</option>
                <option value="butir">butir</option>
                <option value="sdm">sdm</option>
                <option value="sdt">sdt</option>
                <option value="potong">potong</option>
                <option value="lembar">lembar</option>
                <option value="siung">siung</option>
              </select>
            </div>
            <button type="button" onclick="removeBahan(this)"
              class="w-9 h-9 mt-0.5 rounded-lg bg-dangerbg text-danger hover:bg-danger hover:text-white flex items-center justify-center transition flex-shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full mt-6 bg-accent hover:bg-accenthover text-white text-base font-bold py-4 rounded-xl shadow-[0_4px_15px_rgba(249,115,22,0.3)] transition-all flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        Lanjut ke Review
      </button>
    </form>
  </div>

  <script>
    let bahanIndex = 1;

    function addBahan() {
      const list = document.getElementById('bahan-list');
      const html = `
            <div class="bahan-item flex gap-2 items-start animate-fade-in">
              <div class="flex-1">
                <input type="text" name="bahan[${bahanIndex}][nama]" placeholder="Nama bahan" required
                  class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
              </div>
              <div class="w-20">
                <input type="text" name="bahan[${bahanIndex}][jumlah]" placeholder="100" required
                  class="w-full bg-appbg border border-chartbg/80 rounded-xl px-3 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal text-center">
              </div>
              <div class="w-24">
                <select name="bahan[${bahanIndex}][satuan]" required
                  class="w-full bg-appbg border border-chartbg/80 rounded-xl px-2 py-2.5 text-sm text-textmain font-medium focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner">
                  <option value="gram">gram</option>
                  <option value="ml">ml</option>
                  <option value="buah">buah</option>
                  <option value="butir">butir</option>
                  <option value="sdm">sdm</option>
                  <option value="sdt">sdt</option>
                  <option value="potong">potong</option>
                  <option value="lembar">lembar</option>
                  <option value="siung">siung</option>
                </select>
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
  </script>
@endsection