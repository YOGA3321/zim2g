<x-app-layout>
    <x-slot name="header">
        Kelola Komponen (Kegiatan)
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 uppercase tracking-wider mb-6">Tambah Komponen Baru</h3>
            <form action="{{ route('master-data.areas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kode</label>
                    <input type="text" name="code" placeholder="A, B, C..." required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Komponen</label>
                    <input type="text" name="name" placeholder="Nama komponen..." required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white">
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
                        <th class="px-8 py-4 border-b">Nama Komponen</th>
                        <th class="px-8 py-4 border-b">Jumlah Sub Komponen</th>
                        <th class="px-8 py-4 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($areas as $area)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-8 py-6">
                            <span class="bg-green-600 text-white px-3 py-1 rounded-lg font-bold text-xs">Area {{ $area->code }}</span>
                        </td>
                        <td class="px-8 py-6 text-gray-700 font-semibold text-sm">
                            {{ $area->name }}
                        </td>
                        <td class="px-8 py-6 text-gray-500 text-sm font-medium">
                            {{ $area->components_count }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('master-data.components', $area->id) }}" class="flex items-center space-x-2 bg-white border border-green-200 text-green-700 px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-50 transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    <span>Sub Komponen</span>
                                </a>
                                <a href="{{ route('master-data.areas.edit', $area->id) }}" class="p-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </a>
                                <form id="delete-form-area-{{ $area->id }}" action="{{ route('master-data.areas.destroy', $area->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('area-{{ $area->id }}', 'Komponen ini dan semua sub-komponennya akan dihapus!')" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic">Belum ada komponen yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
