@extends('layouts.admin')

@section('title', "Kelola Foto — Admin HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="font-serif font-extrabold text-3xl md:text-4xl">Foto</h1>
        <p class="text-brand-muted text-sm mt-1">{{ $photos->total() }} sesi foto tersimpan. Klik preview untuk melihat hasil penuh.</p>
    </div>
    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-sm min-w-[760px]">
            <thead><tr class="text-left text-brand-muted border-b"><th class="py-2 pr-4">ID</th><th class="py-2 pr-4">Nama</th><th class="py-2 pr-4">Sosmed</th><th class="py-2 pr-4">Preview</th><th class="py-2 pr-4">Waktu</th><th class="py-2">Aksi</th></tr></thead>
            <tbody>
                @forelse($photos as $p)
                    <tr class="border-b last:border-0">
                        <td class="py-3 pr-4 font-mono">#{{ $p->id }}</td>
                        <td class="py-3 pr-4 font-semibold">{{ $p->visitor_name }}</td>
                        <td class="py-3 pr-4">{{ $p->visitor_social }}</td>
                        <td class="py-3 pr-4">
                            <a href="{{ route('photo.result', $p->id) }}" target="_blank">
                                <img src="{{ asset('storage/' . $p->result_image_path) }}" class="h-20 w-auto rounded-lg border hover:opacity-90 transition" alt="strip {{ $p->id }}">
                            </a>
                        </td>
                        <td class="py-3 pr-4 text-brand-muted">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3 flex gap-2">
                            <a href="{{ route('admin.photos.download', $p->id) }}" class="border border-gray-300 rounded-full px-3 py-1 text-xs font-semibold hover:bg-gray-100">Download</a>
                            <form method="POST" action="{{ route('admin.photos.delete', $p->id) }}" onsubmit="return confirm('Hapus sesi foto #{{ $p->id }}?')">@csrf @method('DELETE')<button class="border border-red-300 text-red-600 rounded-full px-3 py-1 text-xs font-semibold hover:bg-red-50">Hapus</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-brand-muted">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $photos->links() }}</div>
    </div>
@endsection
