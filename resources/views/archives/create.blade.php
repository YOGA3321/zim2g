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
                        <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Tahun</label>
                        <select name="year" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-600 transition-all hover:bg-white cursor-pointer">
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for($y = 2024; $y <= $currentYear + 10; $y++)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Sub Komponen / Indikator</label>
                        <select name="zi_sub_component_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-600 transition-all hover:bg-white cursor-pointer">
                            @foreach($areas as $area)
                                @foreach($area->components as $ziComponent)
                                    <optgroup label="Area {{ $area->code }}: {{ $ziComponent->name }}">
                                        @foreach($ziComponent->subComponents as $sub)
                                            <option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Pilih File</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center hover:border-green-400 transition-all bg-gray-50 group">
                        <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-4">
                            <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-all">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="text-gray-500">
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
</x-app-layout>
