{{-- Layout panel admin: sidebar navigasi + konten. Halaman admin
     mewarisi layout ini dan mengisi section 'admin-content'.
     Catatan: jangan tulis directive Blade (@section, dll) di dalam komentar
     seperti ini — Blade tetap mengeksekusinya dan markup rusak. --}}
@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-6xl px-6 pb-16 pt-10">
    <div class="grid items-start gap-6 lg:grid-cols-[240px_minmax(0,1fr)]">

        <aside class="card p-4 lg:sticky lg:top-24">
            <p class="px-3 pt-1 text-2xs font-bold uppercase tracking-[.18em] text-brand-orange">Panel Admin</p>

            <nav class="mt-3 flex gap-1 overflow-x-auto lg:flex-col" aria-label="Navigasi admin">
                <x-admin-nav-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" label="Dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></svg>
                </x-admin-nav-item>
                <x-admin-nav-item :href="route('admin.photos')" :active="request()->routeIs('admin.photos*')" label="Foto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z" stroke-linejoin="round"/><circle cx="12" cy="13.5" r="3.4"/></svg>
                </x-admin-nav-item>
                <x-admin-nav-item :href="route('admin.templates')" :active="request()->routeIs('admin.templates*')" label="Template">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 5v14M17 5v14" stroke-linecap="round"/></svg>
                </x-admin-nav-item>
                <x-admin-nav-item :href="route('admin.users')" :active="request()->routeIs('admin.users*')" label="Pengguna">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 19c.6-3 2.8-4.5 5.5-4.5s4.9 1.5 5.5 4.5" stroke-linecap="round"/><circle cx="17" cy="9" r="2.4"/><path d="M16 14.6c2.3.2 3.9 1.6 4.4 4" stroke-linecap="round"/></svg>
                </x-admin-nav-item>
            </nav>

            <div class="my-3 border-t border-brand-line"></div>

            <nav aria-label="Navigasi tambahan">
                <x-admin-nav-item :href="route('home')" :active="false" label="Lihat Website">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.5 2.3 3.8 5.2 3.8 8.5s-1.3 6.2-3.8 8.5c-2.5-2.3-3.8-5.2-3.8-8.5s1.3-6.2 3.8-8.5z"/></svg>
                </x-admin-nav-item>
            </nav>
        </aside>

        <div class="min-w-0">
            @if (session('success'))
                <div role="status" class="mb-4 rounded-2xl border border-brand-orange/25 bg-brand-orange-soft px-5 py-4 text-sm font-medium text-brand-orange-hover">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <p class="font-semibold">Ada yang perlu diperbaiki:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('admin-content')
        </div>
    </div>
</section>
@endsection