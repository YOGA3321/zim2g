<x-app-layout>
    <x-slot name="header">
        Semua Arsip Digital
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100">
            <form action="{{ route('archives.index') }}" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-4 flex-1">
                    <select name="year" class="rounded-xl border-gray-200 text-gray-600 focus:ring-green-500 focus:border-green-500 bg-gray-50 py-2 pl-4 pr-10 font-bold transition-all hover:bg-white cursor-pointer" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @php
                            $startYear = 2024;
                            $currentYear = date('Y');
                            $endYear = $currentYear + 10;
                        @endphp
                        @for($y = $startYear; $y <= $endYear; $y++)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <select name="area_id" class="rounded-xl border-gray-200 text-gray-600 focus:ring-green-500 focus:border-green-500 bg-gray-50 py-2 pl-4 pr-10 font-bold transition-all hover:bg-white cursor-pointer" onchange="this.form.submit()">
                        <option value="">Semua Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->code }}. {{ $area->name }}</option>
                        @endforeach
                    </select>

                    @if(Auth::user()->role === 'admin')
                    <select name="user_id" class="rounded-xl border-gray-200 text-gray-600 focus:ring-green-500 focus:border-green-500 bg-gray-50 py-2 pl-4 pr-10 font-bold transition-all hover:bg-white cursor-pointer" onchange="this.form.submit()">
                        <option value="">Semua Pengunggah</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('archives.create') }}" class="bg-[#198754] text-white px-6 py-2 rounded-xl font-bold hover:bg-[#157347] transition-all shadow-md">
                    + Tambah Arsip Baru
                </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                        <th class="px-8 py-4 border-b">No</th>
                        <th class="px-8 py-4 border-b">Tahun</th>
                        <th class="px-8 py-4 border-b">Komponen & Unsur</th>
                        <th class="px-8 py-4 border-b">Pengunggah</th>
                        <th class="px-8 py-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($archives as $archive)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-8 py-6 text-gray-400 text-sm font-medium">{{ $loop->iteration }}</td>
                        <td class="px-8 py-6">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-bold text-sm">{{ $archive->year }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-gray-700 text-sm">
                                {{ $archive->subComponent->component->area->code }}. {{ $archive->subComponent->component->area->name }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $archive->subComponent->code }}. {{ $archive->subComponent->name }}
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center text-[10px] text-white font-black">
                                    {{ strtoupper(substr($archive->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-700">{{ $archive->user->name }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $archive->user->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center space-x-3">
                                <button onclick="previewFile('{{ $archive->google_drive_file_id }}', '{{ $archive->file_name }}')" class="inline-flex flex-col items-center text-blue-600 hover:text-blue-800 group">
                                    <div class="p-2 border border-blue-200 rounded-lg group-hover:bg-blue-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-bold mt-1 uppercase">Preview</span>
                                </button>
                                <a href="{{ $archive->google_drive_link }}" target="_blank" class="inline-flex flex-col items-center text-green-600 hover:text-green-800 group">
                                    <div class="p-2 border border-green-200 rounded-lg group-hover:bg-green-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-bold mt-1 uppercase">Drive</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-400 italic">Belum ada arsip yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
