<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Arsip Digital Zona Integritas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased text-gray-900">
        <div class="flex h-screen bg-gray-100 overflow-hidden">
            <!-- Modular Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navbar -->
                <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 shrink-0">
                    <div>
                        <h2 class="font-black text-xl text-gray-800 tracking-tight">
                            {{ $header ?? 'Dashboard' }}
                        </h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-xs hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-sign-out-alt"></i> LOGOUT
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Scripts -->
        <script>
            function confirmDelete(id, text = "Data akan dihapus permanen!") {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold',
                        cancelButton: 'rounded-xl px-6 py-3 font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form-' + id);
                        if(form) form.submit();
                    }
                });
            }

            function previewFile(fileId, title = 'Preview File') {
                const modal = document.getElementById('previewModal');
                const iframe = document.getElementById('previewIframe');
                const titleEl = document.getElementById('previewTitle');
                
                iframe.src = `https://drive.google.com/file/d/${fileId}/preview`;
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
                        customClass: { popup: 'rounded-3xl' }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ session('error') }}",
                        customClass: { popup: 'rounded-3xl' }
                    });
                @endif
            });
        </script>

        <!-- Global Preview Modal -->
        <div id="previewModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="bg-white w-full max-w-6xl h-[90vh] rounded-3xl overflow-hidden shadow-2xl flex flex-col animate-in fade-in zoom-in duration-300">
                <div class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center">
                    <div class="flex items-center gap-3 text-gray-800">
                        <i class="fas fa-file-alt text-blue-500"></i>
                        <h3 id="previewTitle" class="text-lg font-black tracking-tight">Preview Berkas</h3>
                    </div>
                    <button onclick="closePreview()" class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-xl text-gray-500 hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex-1 bg-gray-50 relative">
                    <iframe id="previewIframe" src="" class="w-full h-full border-none"></iframe>
                </div>
            </div>
        </div>

        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        </style>
    </body>
</html>
