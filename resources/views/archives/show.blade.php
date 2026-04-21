<x-app-layout>
    <x-slot name="header">
        Detail Wadah Arsip
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6 pb-12">
        <!-- Back Button -->
        <a href="{{ route('archives.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-800 transition-colors gap-2 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Main Info -->
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
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi / Keterangan</p>
                            <div class="text-gray-600 leading-relaxed bg-white border border-dashed border-gray-200 p-6 rounded-2xl">
                                {{ $archive->description ?? 'Tidak ada deskripsi tambahan untuk wadah ini.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Status / Preview -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-file-alt text-blue-500"></i> Berkas Terlampir
                    </h4>

                    @if($archive->file_name)
                        <div class="flex flex-col md:flex-row items-center gap-6 p-6 bg-blue-50/50 border border-blue-100 rounded-2xl">
                            <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-blue-200">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-1 text-center md:text-left min-w-0">
                                <h5 class="font-bold text-gray-900 truncate">{{ $archive->file_name }}</h5>
                                <p class="text-sm text-gray-500">Tersimpan aman di Google Drive</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ $archive->google_drive_link }}" target="_blank" 
                                   class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-md flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> Buka Berkas
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 bg-amber-50/50 border border-dashed border-amber-200 rounded-3xl">
                            <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5 class="font-bold text-amber-900">Belum Ada File</h5>
                            <p class="text-sm text-amber-700 mt-1 mb-6">Wadah ini telah dibuat, namun berkas belum diunggah.</p>
                            
                            <button onclick="document.getElementById('uploadArea').classList.toggle('hidden')" 
                                    class="px-6 py-2 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition-all">
                                Unggah Sekarang
                            </button>

                            <div id="uploadArea" class="hidden mt-8 max-w-sm mx-auto px-6">
                                <form action="{{ route('archives.update-file', $archive->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                                    @csrf
                                    <div class="space-y-2">
                                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Pilih File PDF/Gambar</label>
                                        <input type="file" name="file" required class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-amber-500">
                                    </div>
                                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 transition-all">
                                        MULAI UNGGAH
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Metadata -->
            <div class="space-y-6">
                <div class="bg-[#1a237e] p-8 rounded-3xl shadow-xl text-white">
                    <h4 class="font-bold text-lg mb-6 opacity-80 tracking-tight">Informasi Arsip</h4>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black opacity-50 tracking-widest">Diinput Oleh</p>
                                <p class="font-bold">{{ $archive->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black opacity-50 tracking-widest">Tanggal Input</p>
                                <p class="font-bold">{{ $archive->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-hdd text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black opacity-50 tracking-widest">Penyimpanan</p>
                                <p class="font-bold">Google Drive Cloud</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="bg-red-50 p-6 rounded-3xl border border-red-100">
                    <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-4">Zona Bahaya</p>
                    <form action="{{ route('archives.destroy', $archive->id) }}" method="POST" onsubmit="return confirm('Hapus seluruh wadah arsip ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-white text-red-600 font-bold rounded-xl border border-red-200 hover:bg-red-600 hover:text-white transition-all">
                            Hapus Wadah
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
