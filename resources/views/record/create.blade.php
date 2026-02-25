@php $activePage = 'add'; @endphp
@extends('layouts.app')
@section('title', 'Tambah Record')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="flex items-center justify-between mb-2">
      <div>
        <h1 class="text-xl font-bold text-textmain">Record Data</h1>
        <p class="text-xs text-textmuted font-medium">{{ $today->translatedFormat('l, d F Y') }}</p>
      </div>
      @if($existingRecord)
        <span class="bg-pastelorange text-accent text-xs font-bold px-3 py-1 rounded-full">Editing</span>
      @endif
    </header>

    <form action="{{ route('record.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf

      {{-- Body Metrics --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm relative overflow-hidden">
        <img src="{{ asset('images/login.png') }}" alt="Watermark"
          class="absolute bottom-[-10px] right-[-20px] w-36 h-36 opacity-50 pointer-events-none rotate-6"
          onerror="this.style.display='none'">
        <div class="relative z-10">
          <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
              </path>
            </svg>
            Metrik Tubuh
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
              <label for="berat_badan" class="block text-xs font-bold text-textmuted mb-2">Berat Badan (kg)</label>
              <input type="number" id="berat_badan" name="berat_badan" step="0.1" placeholder="ex: 92.5"
                value="{{ old('berat_badan', $existingRecord->berat_badan ?? '') }}"
                class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
            </div>
            <div>
              <label for="lingkar_pinggang" class="block text-xs font-bold text-textmuted mb-2">Lingkar Pinggang
                (cm)</label>
              <input type="number" id="lingkar_pinggang" name="lingkar_pinggang" step="0.5" placeholder="ex: 90.0"
                value="{{ old('lingkar_pinggang', $existingRecord->lingkar_pinggang ?? '') }}"
                class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
            </div>
            <div>
              <label for="langkah_kaki" class="block text-xs font-bold text-textmuted mb-2">Langkah Kaki</label>
              <input type="number" id="langkah_kaki" name="langkah_kaki" placeholder="ex: 6500"
                value="{{ old('langkah_kaki', $existingRecord->langkah_kaki ?? '') }}"
                class="w-full bg-appbg border border-chartbg/80 rounded-xl px-4 py-3 text-textmain font-bold focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-inner placeholder:text-chartbg placeholder:font-normal">
            </div>

            <div class="mt-5 border-t border-chartbg/50 pt-5 md:col-span-3">
              <label class="block text-xs font-bold text-textmuted mb-4 flex items-center justify-between">
                <span>Foto Badan</span>
                @if($existingRecord && $existingRecord->foto_badan)
                  <span class="text-accent bg-pastelorange px-2 py-0.5 rounded-md">Foto Tersimpan</span>
                @endif
              </label>

              <div class="relative group cursor-pointer">
                <input type="file" id="foto_badan" name="foto_badan" accept="image/*"
                  class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(event)">
                <div
                  class="w-full h-48 sm:h-64 border-2 opacity-50 border-dashed border-chartbg/80 rounded-2xl bg-appbg flex flex-col items-center justify-center text-textmuted group-hover:bg-pastelorange/30 group-hover:border-accent transition-all overflow-hidden relative shadow-inner">

                  @if($existingRecord && $existingRecord->foto_badan)
                    <img id="image-preview" src="{{ asset('storage/' . $existingRecord->foto_badan) }}" alt="Preview"
                      class="absolute inset-0 w-full h-full object-cover">
                    <div id="upload-overlay"
                      class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-300">
                      <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                          </path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                      </div>
                      <span class="text-white text-sm font-bold">Ganti Foto</span>
                    </div>
                  @else
                    <img id="image-preview" src="#" alt="Preview"
                      class="absolute inset-0 w-full h-full object-cover hidden">
                    <div id="upload-prompt" class="flex flex-col items-center justify-center transition-all duration-300">
                      <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(249,115,22,0.3)] transition-all">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                          </path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                      </div>
                      <span class="text-sm font-bold text-textmain group-hover:text-accent transition-colors">Tap untuk
                        mengupload foto</span>
                      <span class="text-xs font-medium text-textmuted mt-1">PNG, JPG, WEBP (Max 5MB)</span>
                    </div>

                    <div id="upload-overlay"
                      class="absolute inset-0 bg-black/40 opacity-0 flex flex-col items-center justify-center transition-all duration-300 hidden">
                      <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                          </path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                      </div>
                      <span class="text-white text-sm font-bold">Ganti Foto</span>
                    </div>
                  @endif

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Diet Habits --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Pola Makan Harian
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          @foreach($habits as $habit)
            <label
              class="flex items-center p-3.5 bg-appbg border border-chartbg/80 rounded-xl cursor-pointer hover:bg-pastelorange/30 hover:border-pastelpeach transition-all group">
              <input type="checkbox" name="habits[]" value="{{ $habit->id }}" class="peer sr-only" {{ in_array($habit->id, array_keys($existingHabits)) && ($existingHabits[$habit->id] ?? false) ? 'checked' : '' }}>
              <div
                class="w-6 h-6 rounded-lg border-2 border-chartbg bg-white peer-checked:bg-accent peer-checked:border-accent flex items-center justify-center transition-all mr-3 shadow-sm">
                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <span
                class="text-sm font-medium text-textmain group-hover:text-accent transition">{{ $habit->nama_habit }}</span>
            </label>
          @endforeach
        </div>
      </div>

      {{-- Workouts --}}
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
        <h2 class="text-sm font-bold text-textmuted uppercase tracking-wider mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
            </path>
          </svg>
          Olahraga
        </h2>
        <div class="space-y-3">
          @foreach($workouts as $wo)
            <div class="flex items-center justify-between p-3.5 bg-appbg border border-chartbg/80 rounded-xl">
              <div>
                <span class="text-sm font-bold text-textmain">{{ $wo->nama_olahraga }}</span>
                <span class="text-xs text-textmuted ml-1">(target: {{ $wo->target_value }} {{ $wo->satuan }})</span>
              </div>
              <div class="flex items-center gap-2">
                <button type="button" onclick="changeVal(this, -1)"
                  class="w-8 h-8 rounded-lg bg-chartbg/50 hover:bg-accent hover:text-white text-textmuted font-bold flex items-center justify-center transition">−</button>
                <input type="number" name="workout[{{ $wo->id }}]" value="{{ $existingWorkouts[$wo->id] ?? 0 }}" min="0"
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
        {{ $existingRecord ? 'Perbarui Data Hari Ini' : 'Simpan Data Hari Ini' }}
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

    function previewImage(event) {
      const reader = new FileReader();
      const imageField = document.getElementById('image-preview');
      const promptField = document.getElementById('upload-prompt');
      const overlayField = document.getElementById('upload-overlay');

      reader.onload = function () {
        if (reader.readyState === 2) {
          imageField.src = reader.result;
          imageField.classList.remove('hidden');
          if (promptField) promptField.classList.add('hidden');

          if (overlayField) {
            overlayField.classList.remove('hidden');
            overlayField.classList.add('group-hover:opacity-100');
          }
        }
      }

      if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
      }
    }
  </script>
@endsection