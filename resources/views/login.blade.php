@extends('layouts.app')

@section('title', "Masuk — HOLD' MOMENT")

@section('content')

<x-auth-card
    heading="Abadikan momen serumu dalam sekejap."
    title="Selamat datang kembali"
    subtitle="Masuk untuk melanjutkan sesi photobooth-mu."
    :points="[
        'Semua hasil fotomu tersimpan rapi di akun',
        'Buka kembali kapan saja untuk cetak ulang',
        'Kelola template dan sesi foto dari dashboard',
    ]">

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" novalidate>
        @csrf

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email" placeholder="nama@email.com"
                   class="input @error('email') !border-red-400 @enderror"
                   @error('email') aria-invalid="true" aria-describedby="email-err" @enderror>
            @error('email')
                <p id="email-err" class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label">Kata Sandi</label>
            <input id="password" type="password" name="password" required
                   autocomplete="current-password" placeholder="Masukkan kata sandimu"
                   class="input @error('password') !border-red-400 @enderror"
                   @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
            @error('password')
                <p id="password-err" class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-md w-full">Masuk</button>
    </form>

    <p class="mt-8 text-center text-sm text-brand-muted">
        Belum punya akun?
        <a href="{{ route('register') }}" class="link-orange">Daftar gratis</a>
    </p>
</x-auth-card>

@endsection