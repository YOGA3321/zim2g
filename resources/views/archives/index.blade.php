<x-app-layout>
    <x-slot name="header">
        Daftar Seluruh Arsip Digital
    </x-slot>

    <div class="space-y-6">
        <!-- Header Info & Search -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Arsip</h2>
                    <p class="text-gray-500 mt-1">Total terdapat {{ $archives->total() }} dokumen dalam sistem</p>
                </div>
                <div class="flex items-center gap-3">
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('archives.create') }}" 
                       class="px-6 py-3 bg-[#1a237e] text-white font-bold rounded-xl shadow-lg hover:bg-[#0d1642] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Tambah Arsip
                    </a>
                    @endif
                </div>
            </div>

            <!-- Filter Section -->
            <form action="{{ route('archives.index') }}" method="GET" class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama file..." 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <select name="year" class="w-full py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="">Semua Tahun</option>
                    @php $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
                @if(request()->anyFilled(['search', 'year']))
                    <a href="{{ route('archives.index') }}" class="text-red-600 font-semibold flex items-center justify-center hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Arsip / Dokumen</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori (Kegiatan)</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tahun</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($archives as $archive)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl {{ $archive->file_name ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center text-xl shrink-0">
                                        <i class="fas {{ $archive->file_name ? 'fa-file-pdf' : 'fa-file-upload' }}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-gray-800 break-words">{{ $archive->file_name ?? 'File Belum Diunggah' }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed max-w-xs">{{ $archive->description ?? 'Tidak ada deskripsi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-gray-700 text-sm">
                                    {{ $archive->component->area->code }} - {{ $archive->component->area->name }}
                                </div>
                                <div class="text-xs text-blue-600 font-semibold mt-1">
                                    <i class="fas fa-chevron-right text-[10px] mr-1"></i> {{ $archive->component->code }} - {{ $archive->component->name }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold">
                                    {{ $archive->year }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    @if($archive->file_name)
                                        <a href="{{ route('archives.show', $archive->id) }}" 
                                           class="p-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                           title="Lihat Detail Wadah">
                                            <i class="fas fa-folder-open"></i>
                                        </a>
                                    @else
                                        <button onclick="openUploadModal('{{ $archive->id }}', '{{ $archive->component->name }}')"
                                                class="px-4 py-2 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-600 hover:text-white transition-all text-xs font-bold flex items-center gap-2">
                                            <i class="fas fa-upload"></i> Unggah File
                                        </button>
                                    @endif

                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('archives.destroy', $archive->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 block opacity-20"></i>
                                Belum ada arsip yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($archives->hasPages())
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                {{ $archives->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeUploadModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-[#1a237e] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Unggah File Arsip</h3>
                    <button onclick="closeUploadModal()" class="text-white opacity-70 hover:opacity-100"><i class="fas fa-times"></i></button>
                </div>
                <form id="modalUploadForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <p class="text-sm text-gray-500">Mengunggah file untuk sub kegiatan:</p>
                        <p id="modalSubName" class="font-bold text-gray-800"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Pilih File (PDF/Gambar)</label>
                        <input type="file" name="file" required class="w-full border rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Modal Progress Bar -->
                    <div id="modalProgress" class="hidden space-y-2 pt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="modalProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p class="text-[10px] text-gray-500 text-center">Sedang mengunggah ke Google Drive...</p>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="closeUploadModal()" class="px-4 py-2 text-gray-600 font-semibold">Batal</button>
                        <button type="submit" id="modalSubmitBtn" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all flex items-center gap-2">
                            <i class="fas fa-cloud-upload-alt"></i> <span>Unggah Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openUploadModal(archiveId, subName) {
            const modal = document.getElementById('uploadModal');
            const form = document.getElementById('modalUploadForm');
            const subDisplay = document.getElementById('modalSubName');
            
            form.action = `/archives/${archiveId}/update-file`;
            subDisplay.textContent = subName;
            modal.classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }

        document.getElementById('modalUploadForm').addEventListener('submit', function() {
            const btn = document.getElementById('modalSubmitBtn');
            const progress = document.getElementById('modalProgress');
            const progressBar = document.getElementById('modalProgressBar');
            
            btn.disabled = true;
            btn.querySelector('span').textContent = 'Mengunggah...';
            progress.classList.remove('hidden');
            
            let p = 0;
            const intv = setInterval(() => {
                p += Math.random() * 15;
                if (p > 90) { p = 90; clearInterval(intv); }
                progressBar.style.width = p + '%';
            }, 300);
        });
    </script>
</x-app-layout>
