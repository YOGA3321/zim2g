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
                        <th class="px-8 py-4 border-b">Komponen</th>
                        <th class="px-8 py-4 border-b">Deskripsi</th>
                        <th class="px-8 py-4 border-b">Unsur Utama</th>
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
                        <td class="px-8 py-6 text-center">
                            <a href="{{ $archive->google_drive_link }}" target="_blank" class="inline-flex items-center space-x-1 border border-green-200 text-green-600 px-3 py-1 rounded-lg hover:bg-green-50 transition-all text-[10px] font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                <span>Buka</span>
                            </a>
                        </td>
                        <td class="px-8 py-6 text-center text-xs text-gray-500 font-medium">
                            {{ $archive->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center space-x-2">
                                <button class="p-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                
                                <form id="delete-form-arc-{{ $archive->id }}" action="{{ route('archives.destroy', $archive->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('arc-{{ $archive->id }}', 'Arsip ini akan dihapus permanen!')" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-12 text-center text-gray-400 italic">Belum ada arsip saya.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
