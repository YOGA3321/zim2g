<x-app-layout>
    <x-slot name="header">
        Detail Wadah Arsip
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6 pb-12" x-data="fileUpload()">
        <!-- Back Button -->
        <a href="{{ route('archives.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-800 transition-colors gap-2 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Folder Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6">
                        <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-black text-lg">
                            {{ $archive->year }}
                        </span>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-black text-blue-500 uppercase tracking-widest mb-2">Kegiatan (Area)</p>
                            <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                                {{ $archive->component->area->code }} - {{ $archive->component->area->name }}
                            </h2>
                        </div>

                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Sub Kegiatan (Komponen)</p>
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $archive->component->code }} - {{ $archive->component->name }}
                            </h3>
                        </div>

                        <div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 text-gray-400">Deskripsi / Keterangan</p>
                            <div class="text-gray-600 leading-relaxed bg-white border border-dashed border-gray-200 p-6 rounded-2xl">
                                {{ $archive->description ?? 'Tidak ada deskripsi tambahan untuk wadah ini.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Files List -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-folder-open text-blue-500"></i> Daftar Berkas
                        </h4>
                        <button @click="showUpload = !showUpload" 
                                class="px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all text-sm flex items-center gap-2 shadow-lg shadow-blue-100">
                            <i class="fas fa-plus"></i> Tambah Berkas
                        </button>
                    </div>

                    <!-- Modern Upload Area -->
                    <div x-show="showUpload" x-transition class="mb-10">
                        <div class="p-10 border-2 border-dashed border-blue-200 bg-blue-50/50 rounded-3xl text-center relative group transition-all hover:border-blue-400">
                            <input type="file" @change="handleFile($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="fileInput">
                            
                            <div class="space-y-4" x-show="!isUploading">
                                <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-xl shadow-blue-100 transition-transform group-hover:scale-110">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div>
                                    <h5 class="text-lg font-bold text-gray-800">Tarik Berkas Kesini</h5>
                                    <p class="text-sm text-gray-400">Atau klik untuk memilih berkas dari komputer</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="space-y-4" x-show="isUploading">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-black inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                                Mengunggah...
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-black inline-block text-blue-600" x-text="progress + '%'"></span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-blue-200">
                                        <div :style="'width: ' + progress + '%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 transition-all duration-300"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @forelse($archive->files as $file)
                        <div class="group flex items-center gap-4 p-5 border border-gray-100 rounded-2xl mb-4 hover:border-blue-200 hover:bg-blue-50/30 transition-all">
                            <div class="w-14 h-14 bg-gray-100 group-hover:bg-blue-600 group-hover:text-white rounded-2xl flex items-center justify-center text-2xl transition-all shadow-sm">
                                @php
                                    $ext = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                    $icon = match(strtolower($ext)) {
                                        'pdf' => 'fa-file-pdf text-red-500',
                                        'doc', 'docx' => 'fa-file-word text-blue-500',
                                        'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                        'jpg', 'jpeg', 'png' => 'fa-file-image text-amber-500',
                                        default => 'fa-file-alt text-gray-400'
                                    };
                                @endphp
                                <i class="fa-solid {{ $icon }} group-hover:text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-bold text-gray-800 truncate text-sm">{{ $file->file_name }}</h5>
                                <div class="flex items-center gap-3 mt-1">
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">
                                        {{ strtoupper($ext) }} &bull; {{ $file->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <p class="text-[10px] text-blue-500 font-black uppercase tracking-widest">
                                        <i class="fa-solid fa-user-circle"></i> {{ $file->user->name ?? 'System' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="previewFile('{{ $file->google_drive_file_id }}', '{{ $file->file_name }}')" 
                                        class="px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-md flex items-center gap-2 text-xs font-bold"
                                        title="Pratinjau">
                                    <i class="fa-solid fa-eye"></i> LIHAT
                                </button>
                                
                                <a href="{{ $file->google_drive_link }}" target="_blank" 
                                   class="p-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-800 hover:text-white transition-all shadow-sm"
                                   title="Buka di Drive">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>

                                @if(auth()->user()->role === 'admin' || $archive->user_id === auth()->id())
                                <form id="delete-file-{{ $file->id }}" action="{{ route('archives.destroy-file', $file->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="confirmDeleteFile({{ $file->id }})" 
                                            class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 border-2 border-dashed border-gray-100 rounded-3xl">
                            <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5 class="font-bold text-gray-400 text-lg">Belum ada berkas</h5>
                            <p class="text-sm text-gray-400 mt-2">Wadah ini masih kosong. Silakan unggah berkas baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Info Card -->
            <div class="space-y-6">
                <div class="bg-[#1a237e] p-8 rounded-3xl shadow-xl text-white">
                    <h4 class="font-bold text-lg mb-8 opacity-80 tracking-tight">Informasi Wadah</h4>
                    
                    <div class="space-y-8">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shadow-inner">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black opacity-50 tracking-widest mb-1">Penanggung Jawab</p>
                                <p class="font-bold text-lg">{{ $archive->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shadow-inner">
                                <i class="fas fa-calendar-check text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black opacity-50 tracking-widest mb-1">Tanggal Dibuat</p>
                                <p class="font-bold text-lg">{{ $archive->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/10">
                            <div class="flex items-center justify-between text-sm">
                                <span class="opacity-60">Total Berkas</span>
                                <span class="font-black px-3 py-1 bg-white/20 rounded-lg text-xs">{{ $archive->files->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="bg-red-50 p-8 rounded-3xl border border-red-100">
                    <p class="text-xs font-black text-red-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-exclamation-triangle"></i> Zona Bahaya
                    </p>
                    <form id="delete-archive-form" action="{{ route('archives.destroy', $archive->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" @click="confirmDeleteArchive()" 
                                class="w-full py-4 bg-white text-red-600 font-bold rounded-2xl border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                            Hapus Wadah Permanen
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function fileUpload() {
            return {
                showUpload: false,
                isUploading: false,
                progress: 0,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    this.isUploading = true;
                    this.progress = 0;

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('archives.update-file', $archive->id) }}', true);

                    xhr.upload.onprogress = (event) => {
                        if (event.lengthComputable) {
                            this.progress = Math.round((event.loaded / event.total) * 100);
                        }
                    };

                    xhr.onload = () => {
                        if (xhr.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Berkas berhasil diunggah.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'Gagal mengunggah berkas.', 'error');
                            this.isUploading = false;
                        }
                    };

                    xhr.onerror = () => {
                        Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
                        this.isUploading = false;
                    };

                    xhr.send(formData);
                },
                confirmDeleteFile(id) {
                    Swal.fire({
                        title: 'Hapus berkas?',
                        text: "Berkas akan dihapus permanen dari sistem dan Google Drive!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-file-' + id).submit();
                        }
                    })
                },
                confirmDeleteArchive() {
                    Swal.fire({
                        title: 'Hapus seluruh wadah?',
                        text: "Semua data berkas di dalamnya akan ikut terhapus!",
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-archive-form').submit();
                        }
                    })
                }
            }
        }
    </script>
</x-app-layout>
