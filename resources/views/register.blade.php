@extends('layouts.app')

@section('title', "Daftar — HOLD' MOMENT")

@section('content')

<x-auth-card
    heading="Mulai buat kenangan seru bersama teman."
    title="Buat akun baru"
    subtitle="Gratis, cukup beberapa detik untuk mulai."
    :points="[
        'Simpan hasil photostrip tanpa batas',
        'Cetak ulang kapan pun kamu mau',
        'Gratis selamanya, tanpa biaya tersembunyi',
    ]">

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5" novalidate>
        @csrf

        <div>
            <label for="name" class="label">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   autocomplete="name" placeholder="Nama lengkapmu"
                   class="input @error('name') !border-red-400 @enderror"
                   @error('name') aria-invalid="true" aria-describedby="name-err" @enderror>
            @error('name')
                <p id="name-err" class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
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
                   autocomplete="new-password" placeholder="Minimal 8 karakter"
                   class="input @error('password') !border-red-400 @enderror"
                   @error('password') aria-invalid="true" aria-describedby="password-err" @enderror>
            @error('password')
                <p id="password-err" class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   autocomplete="new-password" placeholder="Ulangi kata sandimu" class="input">
        </div>

        <button type="submit" class="btn btn-primary btn-md w-full">Daftar Sekarang</button>
    </form>

    <p class="mt-8 text-center text-sm text-brand-muted">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="link-orange">Masuk di sini</a>
    </p>
</x-auth-card>

@endsection