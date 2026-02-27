@php $activePage = 'calorie'; @endphp
@extends('layouts.app')
@section('title', 'Scan Foto Makanan')
@section('content')
  <div class="max-w-3xl mx-auto space-y-6">
    <header class="flex items-center gap-3 mb-2">
      <a href="{{ route('calorie.index') }}" class="text-textmuted hover:text-accent transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
      <div>
        <h1 class="text-xl font-bold text-textmain"> Scan Foto Makanan</h1>
        <p class="text-xs text-textmuted font-medium">Upload foto, AI akan menganalisis makanan</p>
      </div>
    </header>

    @if(session('error'))
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm font-medium">
        {{ session('error') }}
      </div>
    @endif

    <form action="{{ route('calorie.analyzePhoto') }}" method="POST" enctype="multipart/form-data" id="photoForm">
      @csrf
      <div class="bg-cardbg rounded-2xl border border-chartbg/50 p-6 shadow-sm">
        <div class="relative group cursor-pointer">
          <input type="file" id="foto_makanan" name="foto_makanan" accept="image/*" capture="environment"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMealImage(event)"
            required>
          <div id="upload-area"
            class="w-full h-64 border-2 border-dashed border-chartbg/80 rounded-2xl bg-appbg flex flex-col items-center justify-center text-textmuted group-hover:bg-pastelorange/30 group-hover:border-accent transition-all overflow-hidden relative shadow-inner">
            <img id="meal-preview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden">
            <div id="upload-prompt" class="flex flex-col items-center justify-center">
              <div
                class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(249,115,22,0.3)] transition-all">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                  </path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </div>
              <span class="text-sm font-bold text-textmain group-hover:text-accent transition-colors">Tap untuk mengambil
                foto</span>
              <span class="text-xs font-medium text-textmuted mt-1">atau pilih dari galeri</span>
            </div>
            <div id="upload-overlay"
              class="absolute inset-0 bg-black/40 opacity-0 flex flex-col items-center justify-center transition-all duration-300 hidden group-hover:opacity-100">
              <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                  </path>
                </svg>
              </div>
              <span class="text-white text-sm font-bold">Ganti Foto</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Submit --}}
      <button type="submit" id="analyzeBtn" disabled
        class="w-full mt-4 bg-accent hover:bg-accenthover disabled:bg-chartbg disabled:cursor-not-allowed text-white text-base font-bold py-4 rounded-xl shadow-[0_4px_15px_rgba(249,115,22,0.3)] transition-all flex items-center justify-center">
        <svg class="w-5 h-5 mr-2 animate-spin hidden" id="spinnerIcon" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <svg class="w-5 h-5 mr-2" id="normalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <span id="btnText">Analisis dengan AI</span>
      </button>
    </form>

    {{-- Tips --}}
    <div class="bg-pastelorange/30 rounded-2xl border border-pastelpeach/50 p-5">
      <h3 class="text-sm font-bold text-accent mb-2">Tips untuk hasil akurat:</h3>
      <ul class="text-xs text-textmuted space-y-1.5">
        <li class="flex items-start gap-2"><span class="text-accent mt-0.5">•</span> Foto makanan dari atas (bird's eye
          view)</li>
        <li class="flex items-start gap-2"><span class="text-accent mt-0.5">•</span> Pastikan pencahayaan cukup terang
        </li>
        <li class="flex items-start gap-2"><span class="text-accent mt-0.5">•</span> Tampilkan seluruh porsi makanan</li>
        <li class="flex items-start gap-2"><span class="text-accent mt-0.5">•</span> Kamu bisa mengedit hasil AI sebelum
          analisis kalori</li>
      </ul>
    </div>
  </div>

  <script>
    function previewMealImage(event) {
      const reader = new FileReader();
      const imageField = document.getElementById('meal-preview');
      const promptField = document.getElementById('upload-prompt');
      const overlayField = document.getElementById('upload-overlay');
      const analyzeBtn = document.getElementById('analyzeBtn');

      reader.onload = function () {
        if (reader.readyState === 2) {
          imageField.src = reader.result;
          imageField.classList.remove('hidden');
          if (promptField) promptField.classList.add('hidden');
          if (overlayField) {
            overlayField.classList.remove('hidden');
          }
          analyzeBtn.disabled = false;
        }
      }

      if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
      }
    }

    document.getElementById('photoForm').addEventListener('submit', function () {
      const btn = document.getElementById('analyzeBtn');
      const spinner = document.getElementById('spinnerIcon');
      const normalIcon = document.getElementById('normalIcon');
      const btnText = document.getElementById('btnText');

      btn.disabled = true;
      spinner.classList.remove('hidden');
      normalIcon.classList.add('hidden');
      btnText.textContent = 'Sedang menganalisis...';
    });
  </script>
@endsection