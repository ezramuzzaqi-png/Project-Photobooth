@extends('layouts.app')

@section('title', "Kelola Template — Admin HOLD' MOMENT")

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14">
    <div class="flex items-center justify-between">
        <h1 class="font-serif font-extrabold text-3xl">Kelola Template & Background</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-brand-orange font-semibold">← Dashboard</a>
    </div>
    @if(session('success'))
        <div class="mt-4 bg-green-50 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mt-4 bg-red-50 text-red-700 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="font-bold">Upload Template / Background</h2>
        <p class="text-xs text-brand-muted mt-1">Background = gambar penuh di belakang foto (untuk strip persis 100% seperti foto referensi pixel/sky). Frame = overlay PNG transparan di atas foto. Isi salah satu atau keduanya.</p>
        <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data" class="mt-4 grid md:grid-cols-5 gap-3 text-sm items-end">
            @csrf
            <input type="text" name="name" required placeholder="Nama template" class="rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5">
            <select name="layout_type" required class="rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5">
                <option value="2x2">2x2</option>
                <option value="2x3">2x3</option>
                <option value="receipt">receipt (2 strip kembar)</option>
                <option value="strip_1x4">strip 1x4</option>
            </select>
            <label class="text-xs font-semibold">Background
                <input type="file" name="background_image" accept="image/png,image/jpeg" class="mt-1 w-full text-sm font-normal">
            </label>
            <label class="text-xs font-semibold">Frame
                <input type="file" name="frame_image" accept="image/png,image/jpeg" class="mt-1 w-full text-sm font-normal">
            </label>
            <button class="bg-brand-orange text-white rounded-full px-6 py-2.5 font-semibold hover:bg-brand-orange-hover">Upload</button>
        </form>
    </div>

    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
            <thead><tr class="text-left text-brand-muted border-b"><th class="py-2">ID</th><th class="py-2">Preview</th><th class="py-2">Nama</th><th class="py-2">Layout</th><th class="py-2">File</th><th class="py-2">Dibuat</th><th class="py-2">Aksi</th></tr></thead>
            <tbody>
                @forelse($templates as $t)
                    <tr class="border-b last:border-0">
                        <td class="py-3 font-mono">#{{ $t->id }}</td>
                        <td class="py-2">
                            @if($t->background_image)
                                <img src="{{ asset('storage/' . $t->background_image) }}" class="h-16 w-12 object-cover rounded border" alt="">
                            @elseif($t->frame_image)
                                <img src="{{ asset('storage/' . $t->frame_image) }}" class="h-16 w-12 object-cover rounded border bg-brand-card-light" alt="">
                            @else
                                <span class="text-brand-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 font-semibold">{{ $t->name }}</td>
                        <td class="py-3">{{ $t->layout_type }}</td>
                        <td class="py-3 text-xs text-brand-muted">
                            @if($t->background_image) bg: {{ Str::limit($t->background_image, 28) }}<br>@endif
                            @if($t->frame_image) fr: {{ Str::limit($t->frame_image, 28) }} @endif
                            @if(!$t->background_image && !$t->frame_image) — @endif
                        </td>
                        <td class="py-3 text-brand-muted">{{ $t->created_at->format('d M Y') }}</td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.templates.edit', $t->id) }}" class="border border-gray-300 rounded-full px-3 py-1 text-xs font-semibold hover:bg-gray-100">Edit</a>
                                <form method="POST" action="{{ route('admin.templates.destroy', $t->id) }}" onsubmit="return confirm('Hapus template {{ $t->name }}? Foto yang memakainya jadi tanpa desain.')">
                                    @csrf @method('DELETE')
                                    <button class="border border-red-300 text-red-600 rounded-full px-3 py-1 text-xs font-semibold hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-6 text-center text-brand-muted">Belum ada template.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $templates->links() }}</div>
    </div>
</section>
@endsection
