@extends('layouts.app')

@section('title', "Sign Up — HOLD' MOMENT")

@section('content')
<section class="max-w-5xl mx-auto px-6 py-12">
    <div class="rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">
        {{-- Panel Kiri --}}
        <div class="bg-brand-orange text-white p-12">
            <p class="font-serif font-extrabold tracking-widest">HOLD' MOMENT</p>
            <h2 class="font-serif font-extrabold text-3xl md:text-4xl mt-10 leading-tight">Mulai buat kenangan seru bersama teman.</h2>
            <p class="mt-3 text-white/80 text-sm">Daftar gratis dan simpan semua hasil foto strip favoritmu.</p>
        </div>
        {{-- Panel Kanan --}}
        <div class="bg-white p-12">
            <h2 class="font-bold text-2xl">Buat akun baru</h2>
            <p class="text-sm text-brand-muted mt-1">Gratis, cukup beberapa detik untuk mulai</p>

            @if($errors->any())
                <div class="mt-4 bg-red-50 text-red-700 text-sm rounded-xl px-4 py-3">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf
                <label class="block text-sm font-semibold">Nama Lengkap
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukan nama lengkap anda" class="mt-1 rounded-xl border border-gray-300 px-4 py-2.5 w-full font-normal outline-none focus:border-brand-orange">
                </label>
                <label class="block text-sm font-semibold">Email
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com" class="mt-1 rounded-xl border border-gray-300 px-4 py-2.5 w-full font-normal outline-none focus:border-brand-orange">
                </label>
                <label class="block text-sm font-semibold">Password
                    <input type="password" name="password" required placeholder="Password" class="mt-1 rounded-xl border border-gray-300 px-4 py-2.5 w-full font-normal outline-none focus:border-brand-orange">
                </label>
                <label class="block text-sm font-semibold">Konfirmasi Password
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password" class="mt-1 rounded-xl border border-gray-300 px-4 py-2.5 w-full font-normal outline-none focus:border-brand-orange">
                </label>
                <div class="text-right">
                    <a href="#" class="text-sm text-brand-orange font-semibold">Lupa kata sandi?</a>
                </div>
                <button type="submit" class="bg-brand-orange text-white rounded-full py-3 w-full font-semibold hover:bg-brand-orange-hover transition">Daftar</button>
            </form>

            <button class="mt-3 border border-gray-300 rounded-full py-3 w-full font-semibold hover:bg-gray-50 transition">G &nbsp;Daftar dengan Google</button>

            <p class="mt-6 text-center text-sm text-brand-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-brand-orange font-bold">Masuk</a></p>
        </div>
    </div>
</section>
@endsection
