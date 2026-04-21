<x-app-layout>
    <x-slot name="header">
        Daftar Arsip Saya
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex flex-wrap gap-4 items-center justify-between">
            <div>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('archives.create') }}" class="bg-[#198754] text-white px-6 py-2 rounded-xl font-bold hover:bg-[#157347] transition-all shadow-md">
                    + Tambah Arsip Baru
                </a>
                @endif
            </div>
            <div class="flex gap-4">
                <select class="rounded-xl border-gray-200 text-gray-600 focus:ring-green-500 focus:border-green-500 text-sm">
                    <option>Semua Tahun</option>
                    <option>2025</option>
                    <option>2026</option>
                    <option>2027</option>
                    <option>2028</option>
                </select>
                <select class="rounded-xl border-gray-200 text-gray-600 focus:ring-green-500 focus:border-green-500 text-sm">
                    <option>Semua Komponen</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                        <th class="px-8 py-4 border-b">Tahun</th>
                        <th class="px-8 py-4 border-b">Kegiatan</th>
                        <th class="px-8 py-4 border-b">Sub Kegiatan</th>
                        <th class="px-8 py-4 border-b">Deskripsi</th>
                        <th class="px-8 py-4 border-b text-center">Link Arsip</th>
                        <th class="px-8 py-4 border-b text-center">Tanggal</th>
                        <th class="px-8 py-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($archives as $archive)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-8 py-6">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-bold text-xs">{{ $archive->year }}</span>
                        </td>
                        <td class="px-8 py-6 text-gray-700 font-semibold text-xs leading-relaxed max-w-[200px]">
                            {{ $archive->component->area->code }} - {{ $archive->component->area->name }}
                        </td>
                        <td class="px-8 py-6 text-blue-600 font-bold text-xs">
                            {{ $archive->component->code }} - {{ $archive->component->name }}
                        </td>
                        <td class="px-8 py-6 text-gray-500 text-xs leading-relaxed max-w-[200px]">
                            {{ $archive->description ?? '-' }}
                        </td>
                        <td class="px-8 py-6">
                            <a href="{{ route('archives.show', $archive->id) }}" 
                               class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all text-xs shadow-md">
                                <i class="fas fa-folder-open text-[10px]"></i>
                                <span>BUKA WADAH</span>
                            </a>
                        </td>
                        <td class="px-8 py-6 text-center text-xs text-gray-500 font-medium">
                            {{ $archive->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center space-x-2">
                                <form id="delete-form-arc-{{ $archive->id }}" action="{{ route('archives.destroy', $archive->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('arc-{{ $archive->id }}', 'Wadah arsip ini akan dihapus permanen!')" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-gray-400 italic">Belum ada arsip saya.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
