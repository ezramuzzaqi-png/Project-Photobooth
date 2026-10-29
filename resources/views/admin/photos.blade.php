@extends('layouts.admin')

@section('title', "Kelola Foto — Admin HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="page-title">Foto</h1>
        <p class="text-brand-muted text-sm mt-1">{{ $photos->total() }} sesi foto tersimpan. Klik preview untuk melihat hasil penuh.</p>
    </div>
    <div class="card mt-6 overflow-hidden">
        <div class="overflow-x-auto p-6">
            <table class="admin-table">
                <caption class="sr-only">Daftar sesi foto tersimpan beserta aksi unduh dan hapus</caption>
                <thead>
                    <tr>
                        <th scope="col" class="pr-4">ID</th>
                        <th scope="col" class="pr-4">Nama</th>
                        <th scope="col" class="pr-4">Sosmed</th>
                        <th scope="col" class="pr-4">Preview</th>
                        <th scope="col" class="pr-4">Waktu</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($photos as $p)
                        <tr>
                            <td class="pr-4 font-mono text-brand-muted">#{{ $p->id }}</td>
                            <td class="pr-4 font-semibold">{{ $p->visitor_name }}</td>
                            <td class="pr-4">{{ $p->visitor_social }}</td>
                            <td class="pr-4 preview-cell">
                                <a href="{{ route('photo.result', $p->id) }}" target="_blank" rel="noopener"
                                   aria-label="Buka hasil sesi #{{ $p->id }} di tab baru">
                                    <img src="{{ asset('storage/' . $p->result_image_path) }}"
                                         class="h-20 w-auto rounded-xl border border-brand-line hover:opacity-90 transition"
                                         alt="Strip sesi #{{ $p->id }} milik {{ $p->visitor_name }}">
                                </a>
                            </td>
                            <td class="pr-4 text-brand-muted">
                                <time datetime="{{ $p->created_at->toIso8601String() }}">{{ $p->created_at->format('d M Y H:i') }}</time>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.photos.download', $p->id) }}" class="chip px-3 py-1 text-xs">Download</a>
                                    <form method="POST" action="{{ route('admin.photos.delete', $p->id) }}"
                                          onsubmit="return confirm('Hapus sesi foto #{{ $p->id }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="chip px-3 py-1 text-xs text-red-700 hover:border-red-400 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-brand-muted">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 pb-6">{{ $photos->links() }}</div>
    </div>
@endsection
