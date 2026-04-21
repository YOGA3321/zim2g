<x-app-layout>
    <x-slot name="header">
        Tambah Arsip Baru
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-[#198754] p-6 text-white">
                <h3 class="text-xl font-bold uppercase tracking-wider flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <span>Upload Dokumen Arsip</span>
                </h3>
            </div>
            
            <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Pilih Tahun</label>
                        <select name="year" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-600 transition-all hover:bg-white cursor-pointer">
                            <option value="">-- Pilih Tahun --</option>
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for($y = 2024; $y <= $currentYear + 10; $y++)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Pilih Kegiatan (Komponen)</label>
                        <select id="kegiatan" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-600 transition-all hover:bg-white cursor-pointer">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="sub-kegiatan-container" class="hidden">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Pilih Sub Kegiatan (Unsur Utama)</label>
                    <select id="sub_kegiatan" name="zi_component_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-600 transition-all hover:bg-white cursor-pointer">
                        <option value="">-- Pilih Sub Kegiatan --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Pilih File</label>
                    <div id="dropzone" class="relative border-2 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-green-500 hover:bg-green-50 transition-all cursor-pointer group">
                        <input type="file" name="file" id="file_input" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-4">
                            <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-all">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div id="file_info" class="text-gray-500">
                                <span class="font-bold text-green-700">Klik untuk upload</span> atau drag and drop
                                <p class="text-xs mt-1">PDF, DOCX, XLSX (Max 10MB)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Keterangan (Opsional)</label>
                    <textarea name="description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3" placeholder="Masukkan deskripsi singkat file..."></textarea>
                </div>

                <div class="pt-6 flex space-x-4">
                    <button type="submit" class="flex-1 bg-[#198754] text-white py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-[#157347] transition-all shadow-lg">
                        Simpan Arsip
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-8 bg-gray-100 text-gray-500 py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-gray-200 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const areas = @json($areas);
        const kegiatanSelect = document.getElementById('kegiatan');
        const subKegiatanSelect = document.getElementById('sub_kegiatan');
        const subContainer = document.getElementById('sub-kegiatan-container');
        const fileInput = document.getElementById('file_input');
        const fileInfo = document.getElementById('file_info');

        // Handle Dynamic Dropdown
        kegiatanSelect.addEventListener('change', function() {
            const areaId = this.value;
            subKegiatanSelect.innerHTML = '<option value="">-- Pilih Sub Kegiatan --</option>';
            
            if (areaId) {
                const selectedArea = areas.find(a => a.id == areaId);
                if (selectedArea && selectedArea.components.length > 0) {
                    selectedArea.components.forEach(comp => {
                        const option = document.createElement('option');
                        option.value = comp.id;
                        option.text = `${comp.code} - ${comp.name}`;
                        subKegiatanSelect.appendChild(option);
                    });
                    subContainer.classList.remove('hidden');
                } else {
                    subContainer.classList.add('hidden');
                }
            } else {
                subContainer.classList.add('hidden');
            }
        });

        // Handle File Selection Preview
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                fileInfo.innerHTML = `
                    <span class="font-bold text-green-700">${fileName}</span>
                    <p class="text-xs mt-1">Ukuran: ${fileSize} MB</p>
                    <p class="text-xs text-blue-500 font-bold mt-2">Klik lagi untuk mengganti file</p>
                `;
                document.getElementById('dropzone').classList.add('bg-green-50', 'border-green-500');
            }
        });
    </script>
</x-app-layout>
