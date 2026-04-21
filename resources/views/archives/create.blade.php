<x-app-layout>
    <x-slot name="header">
        Tambah Arsip Baru
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-[#1a237e] px-8 py-6">
                <h3 class="text-xl font-bold text-white">Form Pengisian Arsip</h3>
                <p class="text-blue-100 text-sm mt-1">Lengkapi data di bawah untuk menambahkan arsip digital</p>
            </div>

            <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6" id="uploadForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kegiatan (Area) -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-600"></i> Pilih Kegiatan
                        </label>
                        <select id="kegiatan" name="zi_area_id" required
                                class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->code }} - {{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sub Kegiatan (Component) -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-list-ul text-blue-600"></i> Pilih Sub Kegiatan
                        </label>
                        <select id="sub_kegiatan" name="zi_component_id" required disabled
                                class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 opacity-60 cursor-not-allowed">
                            <option value="">-- Pilih Sub Kegiatan --</option>
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-blue-600"></i> Tahun
                        </label>
                        <select name="year" required
                                class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @php $currentYear = date('Y'); @endphp
                            @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i> Deskripsi Singkat
                        </label>
                        <textarea name="description" rows="3" placeholder="Contoh: Laporan Triwulan 1 Manajemen Perubahan"
                               class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"></textarea>
                    </div>
                </div>

                <!-- Upload File Area -->
                <div class="space-y-4">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt text-blue-600"></i> Unggah File <span class="text-gray-400 font-normal">(Opsional untuk Admin)</span>
                    </label>
                    <div id="dropzone" 
                         class="relative group border-2 border-dashed border-gray-300 rounded-2xl p-10 transition-all hover:border-blue-500 hover:bg-blue-50/50 cursor-pointer text-center">
                        <input type="file" name="file" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div id="filePreview" class="space-y-4 pointer-events-none">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-pdf text-3xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-700" id="fileNameDisplay">Klik atau Seret File ke Sini</p>
                                <p class="text-sm text-gray-500">Maksimal ukuran file 10MB (PDF, DOCX, JPG)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar (Hidden by default) -->
                <div id="uploadProgress" class="hidden space-y-2">
                    <div class="flex justify-between text-sm font-semibold text-blue-700">
                        <span>Sedang Mengunggah...</span>
                        <span id="progressText">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 italic text-center">Jangan tutup halaman ini sampai proses selesai.</p>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('archives.index') }}" class="px-6 py-3 text-gray-600 font-semibold hover:text-gray-800 transition-colors">Batal</a>
                    <button type="submit" id="submitBtn"
                            class="px-10 py-3 bg-[#1a237e] text-white font-bold rounded-xl shadow-lg hover:bg-[#0d1642] hover:-translate-y-0.5 transition-all flex items-center gap-3">
                        <i class="fas fa-save"></i> <span>Simpan Arsip</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dropdown Dinamis logic
        const kegiatanSelect = document.getElementById('kegiatan');
        const subKegiatanSelect = document.getElementById('sub_kegiatan');

        kegiatanSelect.addEventListener('change', async function() {
            const areaId = this.value;
            subKegiatanSelect.innerHTML = '<option value="">-- Memuat Sub Kegiatan... --</option>';
            subKegiatanSelect.disabled = true;
            subKegiatanSelect.classList.add('opacity-60', 'cursor-not-allowed');

            if (!areaId) {
                subKegiatanSelect.innerHTML = '<option value="">-- Pilih Sub Kegiatan --</option>';
                return;
            }

            try {
                const response = await fetch(`/api/components-by-area/${areaId}`);
                const components = await response.json();

                subKegiatanSelect.innerHTML = '<option value="">-- Pilih Sub Kegiatan --</option>';
                components.forEach(comp => {
                    const option = document.createElement('option');
                    option.value = comp.id;
                    option.textContent = `${comp.code} - ${comp.name}`;
                    subKegiatanSelect.appendChild(option);
                });

                subKegiatanSelect.disabled = false;
                subKegiatanSelect.classList.remove('opacity-60', 'bg-gray-50', 'cursor-not-allowed');
            } catch (error) {
                console.error('Gagal mengambil data:', error);
                subKegiatanSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
            }
        });

        // File Preview & Loading Logic
        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
                fileNameDisplay.classList.add('text-blue-600');
            }
        });

        uploadForm.addEventListener('submit', function() {
            // Only show progress if file is being uploaded
            if (fileInput.files.length > 0) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.querySelector('span').textContent = 'Mengunggah...';
                
                uploadProgress.classList.remove('hidden');
                
                // Simulated progress since we are using traditional form submit
                // Real progress would need AJAX (XMLHttpRequest / Axios)
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 10;
                    if (progress > 95) {
                        progress = 95;
                        clearInterval(interval);
                    }
                    progressBar.style.width = progress + '%';
                    progressText.textContent = Math.round(progress) + '%';
                }, 400);
            } else {
                // Just change button for no-file submit
                submitBtn.disabled = true;
                submitBtn.querySelector('span').textContent = 'Menyimpan...';
            }
        });
    </script>
</x-app-layout>
