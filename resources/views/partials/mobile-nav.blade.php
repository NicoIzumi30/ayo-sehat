@php $activePage = $activePage ?? ''; @endphp
<nav
  class="md:hidden fixed bottom-0 w-full bg-cardbg px-6 py-3 border-t border-chartbg flex justify-between items-center z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.02)] rounded-t-2xl">
  <a href="{{ route('dashboard') }}"
    class="flex flex-col items-center {{ $activePage === 'home' ? 'text-accent' : 'text-textmuted hover:text-accent transition' }}">
    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
      </path>
    </svg>
    <span class="text-[10px] {{ $activePage === 'home' ? 'font-bold' : 'font-medium' }}">Home</span>
  </a>
  <a href="{{ route('history') }}"
    class="flex flex-col items-center {{ $activePage === 'diary' ? 'text-accent' : 'text-textmuted hover:text-accent transition' }}">
    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
      </path>
    </svg>
    <span class="text-[10px] {{ $activePage === 'diary' ? 'font-bold' : 'font-medium' }}">Diary</span>
  </a>
  <a href="{{ route('record.create') }}"
    class="bg-accent text-white w-12 h-12 rounded-full flex items-center justify-center -mt-8 shadow-[0_8px_20px_rgba(249,115,22,0.3)] border-4 border-cardbg z-20 hover:scale-105 transition-transform">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
    </svg>
  </a>
  <a href="{{ route('target') }}"
    class="flex flex-col items-center {{ $activePage === 'targets' ? 'text-accent' : 'text-textmuted hover:text-accent transition' }}">
    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
    </svg>
    <span class="text-[10px] {{ $activePage === 'targets' ? 'font-bold' : 'font-medium' }}">Targets</span>
  </a>
  <a href="{{ route('profile') }}"
    class="flex flex-col items-center {{ $activePage === 'account' ? 'text-accent' : 'text-textmuted hover:text-accent transition' }}">
    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    <span class="text-[10px] {{ $activePage === 'account' ? 'font-bold' : 'font-medium' }}">Account</span>
  </a>
</nav>