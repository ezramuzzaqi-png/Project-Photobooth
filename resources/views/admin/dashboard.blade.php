@extends('layouts.admin')

@section('title', "Admin Dashboard — HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="text-brand-muted text-sm mt-1">Ringkasan aktivitas photobooth sekilas.</p>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach ([
            ['Total Pengguna', $stats['total_users'], 'text-brand-text'],
            ['Total Foto Sesi', $stats['total_photos'], 'text-brand-orange'],
            ['Foto Hari Ini', $stats['today_photos'], 'text-brand-text'],
            ['Total Template', $stats['total_templates'], 'text-brand-orange'],
        ] as [$label, $value, $tone])
            <div class="card p-6">
                <p class="text-sm text-brand-muted">{{ $label }}</p>
                <p class="mt-1 font-serif text-4xl font-extrabold {{ $tone }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- Tabel Kelola Hasil Sesi Foto --}}
    <div class="card mt-6 overflow-hidden">
        <div class="flex items-center justify-between px-6 pt-6">
            <h2 class="font-bold">Sesi Foto Terbaru</h2>
            <a href="{{ route('admin.photos') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-orange transition hover:gap-2">
                Lihat semua <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        </div>
        <div class="overflow-x-auto p-6">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col" class="pr-4">ID</th>
                        <th scope="col" class="pr-4">Nama Pengunjung</th>
                        <th scope="col" class="pr-4">Media Sosial</th>
                        <th scope="col" class="pr-4">Preview</th>
                        <th scope="col" class="pr-4">Waktu Sesi</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($photos as $p)
                        <tr>
                            <td class="pr-4 font-mono text-brand-muted">#{{ $p->id }}</td>
                            <td class="pr-4 font-semibold">{{ $p->visitor_name }}</td>
                            <td class="pr-4 text-brand-muted">{{ $p->visitor_social }}</td>
                            <td class="pr-4 preview-cell">
                                <a href="{{ route('photo.result', $p->id) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . $p->result_image_path) }}" class="h-20 w-auto rounded-xl border border-brand-line" alt="Strip sesi #{{ $p->id }}">
                                </a>
                            </td>
                            <td class="pr-4 text-brand-muted">{{ $p->created_at->translatedFormat('d M Y · H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.photos.download', $p->id) }}" class="chip px-3 py-1 text-xs">Download</a>
                                    <form method="POST" action="{{ route('admin.photos.delete', $p->id) }}" onsubmit="return confirm('Hapus sesi foto #{{ $p->id }}? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="chip px-3 py-1 text-xs text-red-700 hover:border-red-400 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-brand-muted">Belum ada sesi foto.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
