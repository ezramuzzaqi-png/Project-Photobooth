@extends('layouts.app')

@section('title', "Admin Dashboard — HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="font-serif font-extrabold text-3xl md:text-4xl">Admin Dashboard</h1>
            <p class="text-brand-muted text-sm mt-1">Kelola sesi foto, template frame, dan pengguna.</p>
        </div>
        <div class="flex gap-2 text-sm">
            <a href="{{ route('admin.photos') }}" class="bg-white rounded-full px-5 py-2 font-semibold shadow-sm hover:text-brand-orange">Semua Foto</a>
            <a href="{{ route('admin.templates') }}" class="bg-white rounded-full px-5 py-2 font-semibold shadow-sm hover:text-brand-orange">Template</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-50 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Statistik Ringkasan --}}
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <p class="text-sm text-brand-muted">Total Pengguna</p>
            <p class="font-serif font-extrabold text-4xl mt-1">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <p class="text-sm text-brand-muted">Total Foto Sesi</p>
            <p class="font-serif font-extrabold text-4xl mt-1 text-brand-orange">{{ $stats['total_photos'] }}</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <p class="text-sm text-brand-muted">Foto Hari Ini</p>
            <p class="font-serif font-extrabold text-4xl mt-1">{{ $stats['today_photos'] }}</p>
        </div>
        <div class="bg-brand-dark text-white rounded-3xl p-6 shadow-sm">
            <p class="text-sm text-white/70">Total Template</p>
            <p class="font-serif font-extrabold text-4xl mt-1">{{ $stats['total_templates'] }}</p>
        </div>
    </div>

    {{-- Tabel Kelola Hasil Sesi Foto --}}
    <div class="mt-8 bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-lg">Sesi Foto Terbaru</h2>
            <a href="{{ route('admin.photos') }}" class="text-sm text-brand-orange font-semibold">Lihat semua →</a>
        </div>
        <table class="w-full text-sm min-w-[760px]">
            <thead>
                <tr class="text-left text-brand-muted border-b">
                    <th class="py-2 pr-4">ID</th>
                    <th class="py-2 pr-4">Nama Pengunjung</th>
                    <th class="py-2 pr-4">Media Sosial</th>
                    <th class="py-2 pr-4">Preview</th>
                    <th class="py-2 pr-4">Waktu Sesi</th>
                    <th class="py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($photos as $p)
                    <tr class="border-b last:border-0">
                        <td class="py-3 pr-4 font-mono">#{{ $p->id }}</td>
                        <td class="py-3 pr-4 font-semibold">{{ $p->visitor_name }}</td>
                        <td class="py-3 pr-4 text-brand-muted">{{ $p->visitor_social }}</td>
                        <td class="py-3 pr-4">
                            <a href="{{ route('photo.result', $p->id) }}" target="_blank">
                                <img src="{{ asset('storage/' . $p->result_image_path) }}" class="h-20 w-auto rounded-lg border" alt="strip {{ $p->id }}">
                            </a>
                        </td>
                        <td class="py-3 pr-4 text-brand-muted">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.photos.download', $p->id) }}" class="border border-gray-300 rounded-full px-3 py-1 text-xs font-semibold hover:bg-gray-100">Download</a>
                                <form method="POST" action="{{ route('admin.photos.delete', $p->id) }}" onsubmit="return confirm('Hapus sesi foto #{{ $p->id }}?')">
                                    @csrf @method('DELETE')
                                    <button class="border border-red-300 text-red-600 rounded-full px-3 py-1 text-xs font-semibold hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-brand-muted">Belum ada sesi foto.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 grid md:grid-cols-2 gap-6">
        {{-- Upload Template --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm">
            <h2 class="font-bold text-lg">Upload Template / Background</h2>
            <p class="text-xs text-brand-muted mt-1">Background = gambar di belakang foto (100% strip seperti foto referensi). Frame = overlay transparan di atas foto. Isi salah satu atau keduanya.</p>
            <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data" class="mt-4 space-y-3 text-sm">
                @csrf
                <input type="text" name="name" required placeholder="Nama template (mis. Pixel Sky Receipt)" class="w-full rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5 outline-none focus:border-brand-orange">
                <select name="layout_type" required class="w-full rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5">
                    <option value="2x2">2x2</option>
                    <option value="2x3">2x3</option>
                    <option value="receipt">receipt (2 strip kembar)</option>
                    <option value="strip_1x4">strip 1x4</option>
                </select>
                <label class="block text-xs font-semibold">Background (opsional) — upload gambar strip persis seperti referensi
                    <input type="file" name="background_image" accept="image/png,image/jpeg" class="mt-1 w-full text-sm">
                </label>
                <label class="block text-xs font-semibold">Frame Overlay (opsional) — PNG transparan
                    <input type="file" name="frame_image" accept="image/png,image/jpeg" class="mt-1 w-full text-sm">
                </label>
                <button class="bg-brand-orange text-white rounded-full px-6 py-2.5 font-semibold hover:bg-brand-orange-hover">Upload Template</button>
            </form>
            <div class="mt-4 space-y-2">
                @foreach($templates as $t)
                    <div class="flex items-center gap-3 bg-brand-card-light rounded-xl px-3 py-2 text-sm">
                        @if($t->background_image)
                            <img src="{{ asset('storage/' . $t->background_image) }}" class="w-10 h-12 rounded-lg object-cover border border-black/10 shrink-0" alt="">
                        @elseif($t->frame_image)
                            <img src="{{ asset('storage/' . $t->frame_image) }}" class="w-10 h-12 rounded-lg object-cover border border-black/10 shrink-0 bg-white" alt="">
                        @else
                            <span class="w-10 h-12 rounded-lg bg-white border border-black/10 flex items-center justify-center text-xs shrink-0">—</span>
                        @endif
                        <span class="flex-1 min-w-0"><b class="block truncate">{{ $t->name }}</b> <span class="text-brand-muted text-xs">{{ $t->layout_type }} @if($t->background_image) • bg @endif @if($t->frame_image) • frame @endif</span></span>
                        <span class="flex gap-1 shrink-0">
                            <a href="{{ route('admin.templates.edit', $t->id) }}" class="bg-white border border-gray-300 rounded-full px-3 py-1 text-xs font-semibold hover:bg-gray-100">Edit</a>
                            <form method="POST" action="{{ route('admin.templates.destroy', $t->id) }}" onsubmit="return confirm('Hapus template {{ $t->name }}?')">
                                @csrf @method('DELETE')
                                <button class="bg-white border border-red-300 text-red-600 rounded-full px-3 py-1 text-xs font-semibold hover:bg-red-50">Hapus</button>
                            </form>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kelola User --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
            <h2 class="font-bold text-lg">Pengguna Terdaftar</h2>
            <table class="w-full text-sm mt-4 min-w-[420px]">
                <thead><tr class="text-left text-brand-muted border-b"><th class="py-2">Nama</th><th class="py-2">Email</th><th class="py-2">Role</th><th class="py-2">Aksi</th></tr></thead>
                <tbody>
                    @foreach($users as $u)
                        <tr class="border-b last:border-0">
                            <td class="py-2">{{ $u->name }}</td>
                            <td class="py-2 text-brand-muted">{{ $u->email }}</td>
                            <td class="py-2"><span class="bg-brand-card-light rounded-full px-3 py-1 text-xs font-bold">{{ $u->role }}</span></td>
                            <td class="py-2">
                                <form method="POST" action="{{ route('admin.users.role', $u->id) }}" class="flex gap-1">
                                    @csrf @method('PATCH')
                                    <select name="role" class="text-xs border rounded-full px-2 py-1">
                                        <option value="user" @selected($u->role==='user')>user</option>
                                        <option value="admin" @selected($u->role==='admin')>admin</option>
                                    </select>
                                    <button class="text-xs bg-brand-dark text-white rounded-full px-3 py-1">OK</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
