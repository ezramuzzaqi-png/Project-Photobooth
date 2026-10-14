{{-- Layout panel admin: sidebar navigasi + konten. Halaman admin
     extends layout ini dan mengisi @section('admin-content'). --}}
@extends('layouts.app')

@section('content')
<section class="max-w-6xl mx-auto px-6 pt-10 pb-14">
    <div class="grid lg:grid-cols-[240px_minmax(0,1fr)] gap-6 items-start">
        <aside class="bg-white rounded-3xl p-4 shadow-sm lg:sticky lg:top-4">
            <p class="px-3 pt-1 text-[11px] font-bold tracking-widest text-brand-muted">ADMIN PANEL</p>
            <nav class="mt-2 flex lg:flex-col flex-row gap-1 overflow-x-auto">
                <x-admin-nav-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" label="Dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 shrink-0"><rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></svg>
                </x-admin-nav-item>
                <x-admin-nav-item :href="route('admin.photos')" :active="request()->routeIs('admin.photos*')" label="Foto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 shrink-0"><path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z" stroke-linejoin="round"/><circle cx="12" cy="13.5" r="3.4"/></svg>
                </x-admin-nav-item>
                <x-admin-nav-item :href="route('admin.templates')" :active="request()->routeIs('admin.templates*')" label="Template">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 shrink-0"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 5v14M17 5v14" stroke-linecap="round"/></svg>
                </x-admin-nav-item>
                <x-admin-nav-item :href="route('admin.users')" :active="request()->routeIs('admin.users*')" label="Pengguna">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 shrink-0"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 19c.6-3 2.8-4.5 5.5-4.5s4.9 1.5 5.5 4.5" stroke-linecap="round"/><circle cx="17" cy="9" r="2.4"/><path d="M16 14.6c2.3.2 3.9 1.6 4.4 4" stroke-linecap="round"/></svg>
                </x-admin-nav-item>
            </nav>
            <div class="my-3 border-t border-black/10"></div>
            <nav class="flex lg:flex-col flex-row gap-1 overflow-x-auto">
                <x-admin-nav-item :href="route('home')" :active="false" label="Lihat Website">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 shrink-0"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.5 2.3 3.8 5.2 3.8 8.5s-1.3 6.2-3.8 8.5c-2.5-2.3-3.8-5.2-3.8-8.5s1.3-6.2 3.8-8.5z"/></svg>
                </x-admin-nav-item>
            </nav>
        </aside>

        <div class="min-w-0">
            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-700 rounded-xl px-4 py-3 text-sm">
                    <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('admin-content')
        </div>
    </div>
</section>
@endsection
