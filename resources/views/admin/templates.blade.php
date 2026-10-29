@extends('layouts.admin')

@section('title', "Kelola Template — Admin HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="page-title">Template</h1>
        <p class="text-brand-muted text-sm mt-1">Upload background & frame, lalu kelola desain yang tampil di kamera.</p>
    </div>

    <div class="card mt-6 p-6">
        <h2 class="font-bold">Upload Template / Background</h2>
        <p class="text-xs text-brand-muted mt-1">Background = gambar penuh di belakang foto (untuk strip persis 100% seperti foto referensi pixel/sky). Frame = overlay PNG transparan di atas foto. Isi salah satu atau keduanya.</p>
        <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data" class="mt-4 grid md:grid-cols-5 gap-3 text-sm items-end">
            @csrf
            <div>
                <label for="tpl-name" class="label text-xs">Nama template</label>
                <input id="tpl-name" type="text" name="name" required placeholder="Nama template" class="input py-2.5">
                @error('name') <p class="error-text">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tpl-layout" class="label text-xs">Format</label>
                <select id="tpl-layout" name="layout_type" required class="input py-2.5">
                    <option value="2x2">2x2</option>
                    <option value="2x3">2x3</option>
                    <option value="receipt">receipt (2 strip kembar)</option>
                    <option value="strip_1x4">strip 1x4</option>
                </select>
                @error('layout_type') <p class="error-text">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tpl-bg" class="label text-xs">Background</label>
                <input id="tpl-bg" type="file" name="background_image" accept="image/png,image/jpeg" class="mt-1.5 w-full text-sm font-normal">
            </div>
            <div>
                <label for="tpl-frame" class="label text-xs">Frame</label>
                <input id="tpl-frame" type="file" name="frame_image" accept="image/png,image/jpeg" class="mt-1.5 w-full text-sm font-normal">
            </div>
            <button type="submit" class="btn btn-primary btn-md">Upload</button>
        </form>
    </div>

    <div class="card mt-6 overflow-hidden">
        <div class="px-6 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg">Daftar Template</h2>
                <span class="text-xs text-brand-muted">{{ $templates->total() }} template</span>
            </div>
        </div>
        <div class="overflow-x-auto px-6">
            <table class="admin-table">
                <caption class="sr-only">Daftar template photostrip beserta aksi edit dan hapus</caption>
                <thead>
                    <tr>
                        <th scope="col" class="pr-4">ID</th>
                        <th scope="col" class="pr-4">Preview</th>
                        <th scope="col" class="pr-4">Nama</th>
                        <th scope="col" class="pr-4">Layout</th>
                        <th scope="col" class="pr-4">File</th>
                        <th scope="col" class="pr-4">Dibuat</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $t)
                        <tr>
                            <td class="pr-4 font-mono text-brand-muted">#{{ $t->id }}</td>
                            <td class="pr-4">
                                @if($t->background_image)
                                    <img src="{{ asset('storage/' . $t->background_image) }}" class="h-16 w-12 object-cover rounded border border-brand-line" alt="Background template {{ $t->name }}">
                                @elseif($t->frame_image)
                                    <img src="{{ asset('storage/' . $t->frame_image) }}" class="h-16 w-12 object-cover rounded border border-brand-line bg-brand-card-light" alt="Frame template {{ $t->name }}">
                                @elseif($t->layout_type === 'receipt')
                                    <img src="{{ asset('images/receipt-thumb.png') }}" class="h-16 w-12 object-cover rounded border border-brand-line" alt="Thumbnail struk">
                                @else
                                    <span class="text-brand-muted" aria-hidden="true">—</span>
                                @endif
                            </td>
                            <td class="pr-4 font-semibold">{{ $t->name }}</td>
                            <td class="pr-4"><span class="badge-{{ $t->layout_type === 'receipt' ? 'ok' : 'new' }}">{{ ucfirst($t->layout_type) }}</span></td>
                            <td class="pr-4 text-xs text-brand-muted">
                                @if($t->background_image) bg: {{ Str::limit($t->background_image, 28) }}<br>@endif
                                @if($t->frame_image) fr: {{ Str::limit($t->frame_image, 28) }} @endif
                                @if(!$t->background_image && !$t->frame_image) <span aria-hidden="true">—</span> @endif
                            </td>
                            <td class="pr-4 text-brand-muted">
                                <time datetime="{{ $t->created_at->toIso8601String() }}">{{ $t->created_at->format('d M Y') }}</time>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.templates.edit', $t->id) }}" class="chip px-3 py-1 text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.templates.destroy', $t->id) }}"
                                          onsubmit="return confirm('Hapus template {{ $t->name }}? Foto yang memakainya jadi tanpa desain.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="chip px-3 py-1 text-xs text-red-700 hover:border-red-400 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-brand-muted">Belum ada template.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 pt-4 pb-6">{{ $templates->links() }}</div>
    </div>
@endsection
