<x-app-layout>
    <x-slot name="header">
        Edit Sub Komponen: {{ $ziComponent->code }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('master-data.components', $ziComponent->zi_area_id) }}" class="inline-flex items-center space-x-2 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="max-w-4xl bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center space-x-3 mb-6">
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-black uppercase">Area {{ $ziComponent->area->code }}</span>
                <h3 class="font-bold text-gray-700 uppercase tracking-wider">Ubah Data Sub Komponen</h3>
            </div>

            <form action="{{ route('master-data.components.update', $ziComponent->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kode</label>
                    <input type="text" name="code" value="{{ old('code', $ziComponent->code) }}" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Sub Komponen</label>
                    <textarea name="name" rows="4" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white @error('name') border-red-500 @enderror">{{ old('name', $ziComponent->name) }}</textarea>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md uppercase tracking-widest">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
