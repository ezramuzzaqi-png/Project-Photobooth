@extends('layouts.app')

@section('title', "Edit Template — Admin HOLD' MOMENT")

@section('content')
<section class="max-w-2xl mx-auto px-6 pt-10 pb-14">
    <div class="flex items-center justify-between">
        <h1 class="font-serif font-extrabold text-3xl">Edit Template</h1>
        <a href="{{ route('admin.templates') }}" class="text-sm text-brand-orange font-semibold">← Kembali</a>
    </div>
    @if($errors->any())
        <div class="mt-4 bg-red-50 text-red-700 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mt-6 bg-white rounded-3xl p-6 shadow-sm space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-semibold">Background saat ini</p>
                @if($template->background_image)
                    <img src="{{ asset('storage/' . $template->background_image) }}" alt="background" class="mt-2 h-48 w-auto rounded-xl border border-black/10 object-cover" onerror="this.outerHTML='<p class=\'text-xs text-red-600 mt-2\'>File tidak ditemukan.</p>'">
                    <p class="text-xs text-brand-muted mt-1 break-all">{{ $template->background_image }}</p>
                @else
                    <p class="text-xs text-brand-muted mt-2 bg-brand-card-light rounded-xl p-3">Belum ada background.</p>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold">Frame saat ini</p>
                @if($template->frame_image)
                    <img src="{{ asset('storage/' . $template->frame_image) }}" alt="frame" class="mt-2 h-48 w-auto rounded-xl border border-black/10 object-cover bg-brand-card-light" onerror="this.outerHTML='<p class=\'text-xs text-red-600 mt-2\'>File tidak ditemukan.</p>'">
                    <p class="text-xs text-brand-muted mt-1 break-all">{{ $template->frame_image }}</p>
                @else
                    <p class="text-xs text-brand-muted mt-2 bg-brand-card-light rounded-xl p-3">Belum ada frame.</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.templates.update', $template->id) }}" enctype="multipart/form-data" class="space-y-3 text-sm">
            @csrf
            @method('PUT')
            <label class="block font-semibold">Nama Template
                <input type="text" name="name" required value="{{ old('name', $template->name) }}" class="mt-1 w-full rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5 font-normal outline-none focus:border-brand-orange">
            </label>
            <label class="block font-semibold">Layout
                <select name="layout_type" required class="mt-1 w-full rounded-xl border border-gray-300 bg-brand-card-light px-4 py-2.5 font-normal">
                    <option value="2x2" @selected(old('layout_type', $template->layout_type) === '2x2')>2x2</option>
                    <option value="2x3" @selected(old('layout_type', $template->layout_type) === '2x3')>2x3</option>
                    <option value="receipt" @selected(old('layout_type', $template->layout_type) === 'receipt')>receipt (2 strip kembar)</option>
                    <option value="strip_1x4" @selected(old('layout_type', $template->layout_type) === 'strip_1x4')>strip 1x4</option>
                </select>
            </label>
            <label class="block font-semibold">Ganti Background <span class="font-normal text-brand-muted">(kosongkan bila tidak ganti)</span>
                <input type="file" name="background_image" accept="image/png,image/jpeg" class="mt-1 w-full text-sm font-normal">
            </label>
            @if($template->background_image)
                <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="clear_background" value="1"> Hapus background saat simpan</label>
            @endif
            <label class="block font-semibold">Ganti Frame <span class="font-normal text-brand-muted">(kosongkan bila tidak ganti)</span>
                <input type="file" name="frame_image" accept="image/png,image/jpeg" class="mt-1 w-full text-sm font-normal">
            </label>
            @if($template->frame_image)
                <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="clear_frame" value="1"> Hapus frame saat simpan</label>
            @endif
            <button class="bg-brand-orange text-white rounded-full px-8 py-2.5 font-semibold hover:bg-brand-orange-hover">Simpan Perubahan</button>
        </form>
    </div>
</section>
@endsection
