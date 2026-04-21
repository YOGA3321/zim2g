 <x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span>Kelola Pengguna</span>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 uppercase tracking-wider mb-6">Tambah User Baru</h3>
            <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Nama..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email (Google)</label>
                    <input type="email" name="email" placeholder="user@gmail.com" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Username</label>
                    <input type="text" name="username" placeholder="username" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Role</label>
                    <select name="role" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 font-bold text-gray-700 transition-all hover:bg-white cursor-pointer">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-[#198754] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#157347] transition-all shadow-md uppercase tracking-widest">
                        Tambah User
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                        <th class="px-8 py-4 border-b">No</th>
                        <th class="px-8 py-4 border-b">Nama & Email</th>
                        <th class="px-8 py-4 border-b">Username</th>
                        <th class="px-8 py-4 border-b">Role</th>
                        <th class="px-8 py-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-8 py-6 text-gray-400 text-sm font-medium">{{ $loop->iteration }}</td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-gray-700">{{ $user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                        </td>
                        <td class="px-8 py-6 text-blue-600 font-bold text-sm">{{ $user->username }}</td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-lg font-black text-[10px] uppercase tracking-wider {{ $user->role === 'admin' ? 'bg-red-500 text-white' : 'bg-green-600 text-white' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="flex items-center space-x-1 bg-yellow-400 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-yellow-500 transition-all">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    <span>Edit</span>
                                </a>
                                @if($user->id !== Auth::id())
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('{{ $user->id }}', 'User {{ $user->name }} akan dihapus!')" class="flex items-center space-x-1 bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-600 transition-all">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <span>Hapus</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
