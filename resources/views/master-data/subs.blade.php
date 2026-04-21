<x-app-layout>
    <x-slot name="header">
        Sub Komponen: {{ $ziComponent->area->code }}.{{ $ziComponent->code }} - {{ $ziComponent->name }}
    </x-slot>

    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('master-data.components', $ziComponent->zi_area_id) }}" class="inline-flex items-center space-x-2 bg-white border border-gray-200 text-gray-600 px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Komponen</span>
        </a>
    </div>

    <!-- Form Tambah Sub Komponen -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-100 bg-green-50/30">
            <h3 class="font-black text-gray-700 uppercase tracking-widest mb-6 flex items-center space-x-2">
                <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                <span>Tambah Sub Komponen / Indikator</span>
            </h3>
            <form action="{{ route('master-data.sub-components.store', $ziComponent->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                @csrf
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Kode</label>
                    <input type="text" name="code" required placeholder="Contoh: a, b, c..." class="w-full rounded-xl border-gray-200 bg-white focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Nama Sub Komponen</label>
                    <input type="text" name="name" required placeholder="Masukkan nama sub komponen..." class="w-full rounded-xl border-gray-200 bg-white focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700">
                </div>
                <div>
                    <button type="submit" class="w-full bg-[#198754] text-white px-8 py-3 rounded-xl font-black hover:bg-[#157347] transition-all shadow-md uppercase tracking-widest text-xs">
                        Tambah
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                        <th class="px-8 py-4 border-b">Kode</th>
                        <th class="px-8 py-4 border-b">Nama Sub Komponen</th>
                        <th class="px-8 py-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subs as $sub)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-8 py-6">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-black text-[10px]">{{ $sub->code }}</span>
                        </td>
                        <td class="px-8 py-6 text-gray-700 font-bold text-sm leading-relaxed max-w-xl">
                            {{ $sub->name }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center space-x-3">
                                <button class="p-2 bg-yellow-50 text-yellow-600 rounded-xl hover:bg-yellow-500 hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                
                                <form id="delete-form-sub-{{ $sub->id }}" action="{{ route('master-data.sub-components.destroy', $sub->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('sub-{{ $sub->id }}', 'Sub-komponen ini akan dihapus permanen dari sistem!')" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-gray-400 italic font-bold">Belum ada sub komponen yang didaftarkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
