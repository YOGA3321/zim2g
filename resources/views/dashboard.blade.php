<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Google Drive Info -->
        <div class="md:col-span-3 bg-gradient-to-r from-[#1a237e] to-[#283593] rounded-2xl p-6 shadow-xl flex items-center justify-between text-white border border-white/10">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center text-2xl backdrop-blur-sm">
                    <i class="fab fa-google-drive"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold opacity-80 uppercase tracking-wider">Koneksi Google Drive</h3>
                    <p class="text-xl font-bold mt-0.5">
                        {{ $googleSetting->google_email ?? 'Belum Terhubung' }}
                    </p>
                </div>
            </div>
            @if($googleSetting && $googleSetting->refresh_token)
                <div class="px-4 py-2 bg-green-500/20 border border-green-500/30 rounded-lg flex items-center gap-2 text-sm font-bold">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Terhubung
                </div>
            @else
                <a href="{{ route('google.auth') }}" class="px-6 py-2 bg-white text-[#1a237e] font-bold rounded-lg hover:bg-blue-50 transition-all shadow-lg">
                    Hubungkan Sekarang
                </a>
            @endif
        </div>

        <!-- Total Archives -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center text-center">
            <div class="bg-blue-50 p-4 rounded-xl mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider mb-2">Total Arsip</p>
            <h3 class="text-5xl font-black text-gray-800">{{ $totalArchives }}</h3>
        </div>

        <!-- Add Archive (Admin Only) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center text-center">
            <div class="bg-green-50 p-4 rounded-xl mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            </div>
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider mb-4">Tambah Arsip</p>
            <a href="{{ route('archives.create') }}" class="w-full py-3 bg-[#198754] hover:bg-[#157347] text-white font-bold rounded-xl transition-all shadow-lg">Tambah</a>
        </div>

        <!-- View Archives -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center text-center">
            <div class="bg-yellow-50 p-4 rounded-xl mb-4">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider mb-4">Lihat Arsip Lain</p>
            <a href="{{ route('archives.index') }}" class="w-full py-3 bg-[#ffc107] hover:bg-[#e0a800] text-gray-900 font-bold rounded-xl transition-all shadow-lg">Lihat</a>
        </div>
    </div>

    <!-- Google Drive Connection Status -->
    <div class="mb-10">
        @php $googleConnected = \App\Models\GoogleSetting::whereNotNull('refresh_token')->exists(); @endphp
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 {{ $googleConnected ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} rounded-xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Google Drive Status</h4>
                    @if($googleConnected)
                        <p class="text-sm text-green-600 font-semibold">{{ $googleSetting->google_email ?? 'Terhubung (Email tidak tersedia)' }}</p>
                    @else
                        <p class="text-sm text-gray-500">Belum terhubung dengan Google Drive</p>
                    @endif
                </div>
            </div>
            @if(!$googleConnected)
            <div class="flex flex-col items-end space-y-2">
                <a href="{{ route('google.connect') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-bold transition-all shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                    <span>Hubungkan dengan Google</span>
                </a>
                <p class="text-[10px] text-gray-400 font-mono italic">Callback: {{ url('/google/callback') }}</p>
            </div>
            @else
            <div class="flex items-center space-x-4">
                <span class="text-green-600 font-bold flex items-center space-x-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Connected</span>
                </span>
                <form id="disconnect-form" action="{{ route('google.disconnect') }}" method="POST" class="inline">
                    @csrf
                    <button type="button" onclick="confirmDisconnect()" class="bg-red-50 text-red-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-red-100 transition-all">
                        Disconnect
                    </button>
                </form>
            </div>
            <script>
                function confirmDisconnect() {
                    Swal.fire({
                        title: 'Putuskan Koneksi?',
                        text: "Aplikasi tidak akan bisa mengunggah file ke Google Drive sebelum dihubungkan kembali.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Putuskan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('disconnect-form').submit();
                        }
                    })
                }
            </script>
            @endif
        </div>
    </div>

    <div class="bg-[#198754] rounded-t-2xl p-4 flex items-center space-x-3 shadow-lg">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        <h3 class="text-white font-bold uppercase tracking-widest">Instrumen ZI</h3>
    </div>
    <div class="bg-white rounded-b-2xl shadow-xl border border-gray-100 p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $colors = [
                    2025 => 'bg-blue-600',
                    2026 => 'bg-green-600',
                    2027 => 'bg-yellow-500',
                    2028 => 'bg-cyan-600',
                ];
            @endphp
            @foreach($years as $year)
            <div class="{{ $colors[$year] }} rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition-all cursor-pointer">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <svg class="w-8 h-8 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h4 class="text-3xl font-black">{{ $year }}</h4>
                        <p class="text-xs font-medium opacity-80 uppercase tracking-tighter mt-1">Tahun {{ ['Pertama', 'Kedua', 'Ketiga', 'Keempat'][$loop->index] }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-bold">{{ $yearStats[$year] }} file</span>
                    <a href="{{ route('archives.index', ['year' => $year]) }}" class="hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
