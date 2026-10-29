@extends('layouts.admin')

@section('title', "Edit Template — Admin HOLD' MOMENT")

@section('admin-content')
    <div>
        <h1 class="page-title">Edit Template</h1>
        <p class="text-brand-muted text-sm mt-1">{{ $template->name }} • {{ $template->layout_type }}</p>
    </div>

    <div class="card mt-6 p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <p class="text-sm font-semibold">Background saat ini</p>
                @if($template->background_image)
                    <img src="{{ asset('storage/' . $template->background_image) }}"
                         alt="Background template {{ $template->name }}"
                         class="mt-2 h-48 w-auto rounded-xl border border-brand-line object-cover"
                         onerror="this.outerHTML='<p class=\'error-text mt-2\'>File tidak ditemukan.</p>'">
                    <p class="text-xs text-brand-muted mt-1 break-all">{{ $template->background_image }}</p>
                @else
                    <p class="text-xs text-brand-muted mt-2 bg-brand-card-light rounded-xl p-3">Belum ada background.</p>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold">Frame saat ini</p>
                @if($template->frame_image)
                    <img src="{{ asset('storage/' . $template->frame_image) }}"
                         alt="Frame template {{ $template->name }}"
                         class="mt-2 h-48 w-auto rounded-xl border border-brand-line object-cover bg-brand-card-light"
                         onerror="this.outerHTML='<p class=\'error-text mt-2\'>File tidak ditemukan.</p>'">
                    <p class="text-xs text-brand-muted mt-1 break-all">{{ $template->frame_image }}</p>
                @else
                    <p class="text-xs text-brand-muted mt-2 bg-brand-card-light rounded-xl p-3">Belum ada frame.</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.templates.update', $template->id) }}" enctype="multipart/form-data" class="mt-6 max-w-xl space-y-3 text-sm">
            @csrf
            @method('PUT')

            <div>
                <label for="ed-name" class="label">Nama Template</label>
                <input id="ed-name" type="text" name="name" required value="{{ old('name', $template->name) }}"
                       class="input py-2.5 @error('name') !border-red-400 @enderror"
                       @error('name') aria-invalid="true" aria-describedby="ed-name-err" @enderror>
                @error('name') <p id="ed-name-err" class="error-text">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="ed-layout" class="label">Layout</label>
                <select id="ed-layout" name="layout_type" required class="input py-2.5">
                    <option value="2x2" @selected(old('layout_type', $template->layout_type) === '2x2')>2x2</option>
                    <option value="2x3" @selected(old('layout_type', $template->layout_type) === '2x3')>2x3</option>
                    <option value="receipt" @selected(old('layout_type', $template->layout_type) === 'receipt')>receipt (2 strip kembar)</option>
                    <option value="strip_1x4" @selected(old('layout_type', $template->layout_type) === 'strip_1x4')>strip 1x4</option>
                </select>
                @error('layout_type') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="ed-bg" class="label">Ganti Background <span class="font-normal text-brand-muted">(kosongkan bila tidak ganti)</span></label>
                <input id="ed-bg" type="file" name="background_image" accept="image/png,image/jpeg" class="mt-1.5 w-full text-sm font-normal">
            </div>
            @if($template->background_image)
                <label class="flex items-center gap-2 text-xs text-brand-muted">
                    <input type="checkbox" name="clear_background" value="1" class="accent-brand-orange">
                    Hapus background saat simpan
                </label>
            @endif

            <div>
                <label for="ed-frame" class="label">Ganti Frame <span class="font-normal text-brand-muted">(kosongkan bila tidak ganti)</span></label>
                <input id="ed-frame" type="file" name="frame_image" accept="image/png,image/jpeg" class="mt-1.5 w-full text-sm font-normal">
            </div>
            @if($template->frame_image)
                <label class="flex items-center gap-2 text-xs text-brand-muted">
                    <input type="checkbox" name="clear_frame" value="1" class="accent-brand-orange">
                    Hapus frame saat simpan
                </label>
            @endif

            <div class="flex flex-wrap gap-3 pt-1">
                <button type="submit" class="btn btn-primary btn-md">Simpan Perubahan</button>
                <a href="{{ route('admin.templates') }}" class="btn btn-outline btn-md">Batal</a>
            </div>
        </form>
    </div>
@endsection
