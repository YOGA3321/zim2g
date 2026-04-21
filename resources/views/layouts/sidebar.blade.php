<aside class="w-64 bg-[#198754] text-white flex-shrink-0 flex flex-col shadow-2xl z-20">
    <div class="p-6 border-b border-green-700 flex items-center space-x-3 bg-white/5">
        <h1 class="text-lg font-bold uppercase tracking-wider leading-tight">Arsip Digital <span class="text-green-200">ZI</span></h1>
    </div>

    <!-- User Profile Brief -->
    <div class="p-8 text-center border-b border-green-700 bg-gradient-to-b from-white/5 to-transparent">
        <div class="w-20 h-20 bg-gradient-to-tr from-green-400 to-teal-500 rounded-2xl mx-auto mb-4 flex items-center justify-center text-3xl font-black shadow-xl ring-4 ring-white/10">
            {{ substr(auth()->user()->name, 0, 2) }}
        </div>
        <p class="font-bold text-lg truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs text-green-200 font-black uppercase tracking-widest mt-1 opacity-70">{{ auth()->user()->role }}</p>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto custom-scrollbar">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fa-th-large">
            Dashboard
        </x-nav-link>

        <x-nav-link href="{{ route('archives.index') }}" :active="request()->routeIs('archives.index')" icon="fa-archive">
            Lihat Semua Arsip
        </x-nav-link>

        <x-nav-link href="{{ route('archives.my') }}" :active="request()->routeIs('archives.my')" icon="fa-user-tag">
            Arsip Saya
        </x-nav-link>

        @if(auth()->user()->role === 'admin')
        <div class="pt-6 pb-2 px-4">
            <p class="text-[10px] font-black text-blue-300/50 uppercase tracking-[0.2em]">Manajemen</p>
        </div>
        
        <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="fa-users-cog">
            Kelola User
        </x-nav-link>

        <x-nav-link href="{{ route('master-data.index') }}" :active="request()->routeIs('master-data.*')" icon="fa-database">
            Master Data
        </x-nav-link>
        @endif
    </nav>
</aside>
