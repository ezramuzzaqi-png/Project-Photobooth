@extends('layouts.app')

@section('title', "Login — HOLD' MOMENT")

@section('content')
<section class="max-w-5xl mx-auto px-6 py-12">
    <div class="rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">
        {{-- Panel Kiri --}}
        <div class="bg-brand-orange text-white p-12">
            <p class="font-serif font-extrabold tracking-widest">HOLD' MOMENT</p>
            <h2 class="font-serif font-extrabold text-3xl md:text-4xl mt-10 leading-tight">Abadikan momen serumu dalam sekejap.</h2>
            <p class="mt-3 text-white/80 text-sm">Masuk untuk mengakses riwayat foto dan template favoritmu.</p>
        </div>
        {{-- Panel Kanan --}}
        <div class="bg-white p-12">
            <h2 class="font-bold text-2xl">Selamat datang kembali!</h2>
            <p class="text-sm text-brand-muted mt-1">Masuk untuk melanjutkan sesi photobooth-mu</p>

            @if($errors->any())
                <div class="mt-4 bg-red-50 text-red-700 text-sm rounded-xl px-4 py-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <label class="block text-sm font-semibold">Email
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com" class="mt-1 rounded-xl border border-gray-300 px-4 py-2.5 w-full font-normal outline-none focus:border-brand-orange">
                </label>
                <label class="block text-sm font-semibold">Password
                    <input type="password" name="password" required placeholder="Password" class="mt-1 rounded-xl border border-gray-300 px-4 py-2.5 w-full font-normal outline-none focus:border-brand-orange">
                </label>
                <div class="text-right">
                    <a href="#" class="text-sm text-brand-orange font-semibold">Lupa kata sandi?</a>
                </div>
                <button type="submit" class="bg-brand-orange text-white rounded-full py-3 w-full font-semibold hover:bg-brand-orange-hover transition">Masuk</button>
            </form>

            <button class="mt-3 border border-gray-300 rounded-full py-3 w-full font-semibold hover:bg-gray-50 transition">G &nbsp;Lanjutkan dengan Google</button>

            <p class="mt-6 text-center text-sm text-brand-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-brand-orange font-bold">Daftar</a></p>
        </div>
    </div>
</section>
@endsection
