@extends('layouts.admin')

@section('title', "Admin Dashboard — HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="font-serif font-extrabold text-3xl md:text-4xl">Dashboard</h1>
        <p class="text-brand-muted text-sm mt-1">Ringkasan aktivitas photobooth sekilas.</p>
    </div>

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
    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-lg">Sesi Foto Terbaru</h2>
            <a href="{{ route('admin.photos') }}" class="inline-flex items-center gap-1 text-sm text-brand-orange font-semibold">Lihat semua <x-icon name="arrow-right" class="w-4 h-4" /></a>
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
@endsection
