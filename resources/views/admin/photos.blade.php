@extends('layouts.app')

@section('title', "Kelola Foto — Admin HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14">
    <div class="flex items-center justify-between">
        <h1 class="font-serif font-extrabold text-3xl">Semua Sesi Foto</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-brand-orange font-semibold">← Dashboard</a>
    </div>
    @if(session('success'))
        <div class="mt-4 bg-green-50 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-sm min-w-[760px]">
            <thead><tr class="text-left text-brand-muted border-b"><th class="py-2 pr-4">ID</th><th class="py-2 pr-4">Nama</th><th class="py-2 pr-4">Sosmed</th><th class="py-2 pr-4">Preview</th><th class="py-2 pr-4">Waktu</th><th class="py-2">Aksi</th></tr></thead>
            <tbody>
                @forelse($photos as $p)
                    <tr class="border-b last:border-0">
                        <td class="py-3 pr-4 font-mono">#{{ $p->id }}</td>
                        <td class="py-3 pr-4 font-semibold">{{ $p->visitor_name }}</td>
                        <td class="py-3 pr-4">{{ $p->visitor_social }}</td>
                        <td class="py-3 pr-4"><img src="{{ asset('storage/' . $p->result_image_path) }}" class="h-20 w-auto rounded-lg border" alt=""></td>
                        <td class="py-3 pr-4 text-brand-muted">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3 flex gap-2">
                            <a href="{{ route('admin.photos.download', $p->id) }}" class="border border-gray-300 rounded-full px-3 py-1 text-xs font-semibold">Download</a>
                            <form method="POST" action="{{ route('admin.photos.delete', $p->id) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="border border-red-300 text-red-600 rounded-full px-3 py-1 text-xs font-semibold">Hapus</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-brand-muted">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $photos->links() }}</div>
    </div>
</section>
@endsection
