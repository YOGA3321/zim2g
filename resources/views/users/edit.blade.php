<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span>Edit Pengguna: {{ $user->name }}</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100">
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 @error('name') border-red-500 @enderror" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email (Google)</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 @error('email') border-red-500 @enderror" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Username</label>
                            <input type="text" name="username" value="{{ $user->username }}" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 @error('username') border-red-500 @enderror" required>
                            @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Role</label>
                            <select name="role" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" placeholder="*****" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-green-500 focus:border-green-500 py-3 @error('password') border-red-500 @enderror">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('users.index') }}" class="text-gray-500 font-bold hover:text-gray-700 transition-all">Batal</a>
                        <button type="submit" class="bg-[#198754] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#157347] transition-all shadow-md uppercase tracking-widest">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
