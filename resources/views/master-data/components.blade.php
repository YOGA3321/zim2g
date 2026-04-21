<x-app-layout>
    <x-slot name="header">
        Sub Komponen: Area {{ $area->code }}. {{ $area->name }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('master-data.index') }}" class="inline-flex items-center space-x-2 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 uppercase tracking-wider mb-6">Tambah Sub Komponen Baru</h3>
            <form action="{{ route('master-data.components.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <input type="hidden" name="zi_area_id" value="{{ $area->id }}">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kode</label>
                    <input type="text" name="code" placeholder="1.1, 1.2..." required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Sub Komponen</label>
                    <input type="text" name="name" placeholder="Nama sub komponen..." required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white">
                </div>
                <div>
                    <button type="submit" class="w-full bg-[#198754] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#157347] transition-all shadow-md uppercase tracking-widest">
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
                    @forelse($components as $comp)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-8 py-6">
                            <span class="bg-green-700 text-white px-3 py-1 rounded-lg font-bold text-xs">{{ $comp->code }}</span>
                        </td>
                        <td class="px-8 py-6 text-gray-700 font-semibold text-sm leading-relaxed max-w-xl">
                            {{ $comp->name }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('master-data.components.edit', $comp->id) }}" class="flex items-center space-x-1 bg-yellow-400 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-yellow-500 transition-all">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    <span>Edit</span>
                                </a>

                                <form id="delete-form-comp-{{ $comp->id }}" action="{{ route('master-data.components.destroy', $comp->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('comp-{{ $comp->id }}', 'Sub komponen ini akan dihapus permanen!')" class="flex items-center space-x-1 bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-600 transition-all">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-gray-400 italic">Belum ada sub komponen yang didaftarkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
