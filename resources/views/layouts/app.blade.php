<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <aside class="w-64 bg-[#198754] text-white flex-shrink-0 flex flex-col shadow-2xl">
                <div class="p-6 border-b border-green-700 flex items-center space-x-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h1 class="text-lg font-bold uppercase tracking-wider">Arsip Digital ZI</h1>
                </div>
                <div class="p-8 text-center border-b border-green-700">
                    <div class="w-24 h-24 bg-[#008080] rounded-full mx-auto mb-4 flex items-center justify-center text-3xl font-bold shadow-inner">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <p class="font-bold text-lg">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-green-200 capitalize">{{ Auth::user()->role }}</p>
                </div>
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all hover:bg-green-600 {{ request()->routeIs('dashboard') ? 'bg-green-700 shadow-md' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('archives.index') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all hover:bg-green-600 {{ request()->routeIs('archives.index') ? 'bg-green-700 shadow-md' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>Lihat Semua Arsip</span>
                    </a>
                    <a href="{{ route('archives.my') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all hover:bg-green-600 {{ request()->routeIs('archives.my') ? 'bg-green-700 shadow-md' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Arsip Saya</span>
                    </a>
                    
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('archives.create') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all hover:bg-green-600 {{ request()->routeIs('archives.create') ? 'bg-green-700 shadow-md' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Tambah Arsip</span>
                    </a>
                    
                    <div class="pt-6 pb-2 text-xs font-bold uppercase text-green-200 tracking-widest pl-3">Admin</div>
                    <a href="{{ route('users.index') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all hover:bg-green-600 {{ request()->routeIs('users.*') ? 'bg-green-700 shadow-md' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Kelola User</span>
                    </a>

                    <a href="{{ route('master-data.index') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all hover:bg-green-600 {{ request()->routeIs('master-data.index') ? 'bg-green-700 shadow-md' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        <span>Master Data</span>
                    </a>
                    @endif
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-white border-b border-gray-200 flex justify-between items-center px-8 py-4 shadow-sm">
                    <div class="flex items-center space-x-4">
                        <button class="lg:hidden text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <h2 class="text-xl font-semibold text-[#198754]">
                            {{ $header ?? 'Arsip Digital ZI MAN 2 Gresik' }}
                        </h2>
                    </div>
                    <div class="flex items-center space-x-6">
                        <span class="text-gray-500 font-medium">Hi, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 text-red-600 hover:text-white hover:bg-red-600 border border-red-600 px-4 py-2 rounded-xl transition-all font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </header>
                <main class="flex-1 overflow-y-auto p-10 bg-gray-50">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global File Preview Modal -->
        <div id="previewModal" class="fixed inset-0 z-[100] hidden">
            <div class="absolute inset-0 bg-black bg-opacity-75 backdrop-blur-sm" onclick="closePreview()"></div>
            <div class="absolute inset-10 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                    <h3 id="previewTitle" class="font-bold text-gray-700">Preview File</h3>
                    <button onclick="closePreview()" class="p-2 hover:bg-gray-200 rounded-full transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex-1 bg-gray-200">
                    <iframe id="previewIframe" src="" class="w-full h-full border-none"></iframe>
                </div>
            </div>
        </div>

        <script>
            function previewFile(fileId, title = 'Preview File') {
                const modal = document.getElementById('previewModal');
                const iframe = document.getElementById('previewIframe');
                const titleEl = document.getElementById('previewTitle');
                
                // Google Drive Preview URL
                iframe.src = `https://docs.google.com/viewer?srcid=${fileId}&pid=explorer&efb=true&usb=false&a=v&chrome=false&embedded=true`;
                titleEl.innerText = title;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closePreview() {
                const modal = document.getElementById('previewModal');
                const iframe = document.getElementById('previewIframe');
                iframe.src = '';
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Global Notification Handler
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        confirmButtonColor: '#198754',
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#198754',
                    });
                @endif
            });

            // Global Delete Confirmation
            function confirmDelete(id, message = 'Data ini akan dihapus permanen!') {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
        </script>
    </body>

</html>

