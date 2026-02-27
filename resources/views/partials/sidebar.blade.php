@php $activePage = $activePage ?? ''; @endphp
<aside class="hidden md:flex flex-col w-64 bg-cardbg h-screen fixed left-0 top-0 border-r border-chartbg p-6 z-40">
  <div class="text-2xl font-bold text-textmain mb-10 flex items-center">
    <div class="w-16 h-16  rounded-xl mr-3 flex items-center justify-center text-accent">
      <img src="{{ asset('images/logo.png') }}" alt="">
    </div>
    Ayo Sehat
  </div>
  <div class="flex flex-col space-y-2 flex-1">
    <a href="{{ route('dashboard') }}"
      class="flex items-center {{ $activePage === 'home' ? 'text-accent bg-pastelorange/50 font-bold' : 'text-textmuted hover:text-accent hover:bg-gray-50 font-medium' }} px-4 py-3 rounded-xl transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path>
      </svg>Home
    </a>
    <a href="{{ route('history') }}"
      class="flex items-center {{ $activePage === 'diary' ? 'text-accent bg-pastelorange/50 font-bold' : 'text-textmuted hover:text-accent hover:bg-gray-50 font-medium' }} px-4 py-3 rounded-xl transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
        </path>
      </svg>Diary Log
    </a>
    <a href="{{ route('calorie.index') }}"
      class="flex items-center {{ $activePage === 'calorie' ? 'text-accent bg-pastelorange/50 font-bold' : 'text-textmuted hover:text-accent hover:bg-gray-50 font-medium' }} px-4 py-3 rounded-xl transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
        </path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
      </svg>Kalori
    </a>
    <a href="{{ route('target') }}"
      class="flex items-center {{ $activePage === 'targets' ? 'text-accent bg-pastelorange/50 font-bold' : 'text-textmuted hover:text-accent hover:bg-gray-50 font-medium' }} px-4 py-3 rounded-xl transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
      </svg>Targets
    </a>
    <a href="{{ route('profile') }}"
      class="flex items-center {{ $activePage === 'account' ? 'text-accent bg-pastelorange/50 font-bold' : 'text-textmuted hover:text-accent hover:bg-gray-50 font-medium' }} px-4 py-3 rounded-xl transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
      </svg>Account
    </a>
  </div>
  <a href="{{ route('record.create') }}"
    class="mt-auto bg-accent text-white font-bold py-3 rounded-xl flex justify-center items-center shadow-[0_4px_15px_rgba(249,115,22,0.3)] hover:bg-accenthover transition">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>Add Record
  </a>
</aside>