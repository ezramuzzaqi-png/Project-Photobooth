@extends('layouts.admin')

@section('title', "Kelola Pengguna — Admin HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="page-title">Pengguna</h1>
        <p class="text-brand-muted text-sm mt-1">{{ $users->total() }} akun terdaftar. Ubah role untuk memberi/mencabut akses admin.</p>
    </div>
    <div class="card mt-6 overflow-hidden">
        <div class="overflow-x-auto p-6">
            <table class="admin-table">
                <caption class="sr-only">Daftar pengguna terdaftar beserta pengaturan role</caption>
                <thead>
                    <tr>
                        <th scope="col" class="pr-4">Nama</th>
                        <th scope="col" class="pr-4">Email</th>
                        <th scope="col" class="pr-4">Role</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td class="pr-4 font-semibold">{{ $u->name }}</td>
                            <td class="pr-4 text-brand-muted">{{ $u->email }}</td>
                            <td class="pr-4">
                                {{-- brand-orange-ink, bukan brand-orange: di atas
                                     orange/10, brand-orange hanya 4.37:1 (gagal AA
                                     untuk teks kecil). brand-orange-ink 5.47:1. --}}
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $u->role === 'admin' ? 'bg-brand-orange/10 text-brand-orange-ink' : 'bg-brand-card-light text-brand-text' }}">{{ $u->role }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.role', $u->id) }}" class="flex gap-2">
                                    @csrf @method('PATCH')
                                    {{-- Select tanpa nama aksesibel = 10 dropdown identik
                                         yang tak satu pun bisa dibedakan AT. --}}
                                    <label for="role-{{ $u->id }}" class="sr-only">Role untuk {{ $u->name }}</label>
                                    <select id="role-{{ $u->id }}" name="role" class="input w-auto rounded-full py-1.5 text-xs">
                                        <option value="user" @selected($u->role === 'user')>user</option>
                                        <option value="admin" @selected($u->role === 'admin')>admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-dark btn-sm text-xs">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-brand-muted">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 pb-6">{{ $users->links() }}</div>
    </div>
@endsection
