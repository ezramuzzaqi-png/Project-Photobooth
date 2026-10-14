@extends('layouts.admin')

@section('title', "Kelola Pengguna — Admin HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="font-serif font-extrabold text-3xl md:text-4xl">Pengguna</h1>
        <p class="text-brand-muted text-sm mt-1">{{ $users->total() }} akun terdaftar. Ubah role untuk memberi/mencabut akses admin.</p>
    </div>
    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-sm min-w-[560px]">
            <thead><tr class="text-left text-brand-muted border-b"><th class="py-2 pr-4">Nama</th><th class="py-2 pr-4">Email</th><th class="py-2 pr-4">Role</th><th class="py-2">Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $u)
                    <tr class="border-b last:border-0">
                        <td class="py-3 pr-4 font-semibold">{{ $u->name }}</td>
                        <td class="py-3 pr-4 text-brand-muted">{{ $u->email }}</td>
                        <td class="py-3 pr-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $u->role === 'admin' ? 'bg-brand-orange/10 text-brand-orange' : 'bg-brand-card-light text-brand-text' }}">{{ $u->role }}</span>
                        </td>
                        <td class="py-3">
                            <form method="POST" action="{{ route('admin.users.role', $u->id) }}" class="flex gap-2">
                                @csrf @method('PATCH')
                                <select name="role" class="text-xs border border-gray-300 rounded-full px-3 py-1.5 bg-white">
                                    <option value="user" @selected($u->role === 'user')>user</option>
                                    <option value="admin" @selected($u->role === 'admin')>admin</option>
                                </select>
                                <button class="text-xs bg-brand-dark text-white rounded-full px-4 py-1.5 font-semibold hover:bg-black transition">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-brand-muted">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
@endsection
