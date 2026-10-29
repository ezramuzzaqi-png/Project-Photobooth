{{-- Kartu auth split: panel kiri gelap (brand), panel kanan form.
     Pakai: <x-auth-card title="..." subtitle="..." :points="[...]">
               <form>...</form>
           </x-auth-card>
--}}
@props([
    'title',
    'subtitle',
    'heading',
    'points' => [],
])

<div class="safe-x mx-auto max-w-5xl px-6 py-14 page-enter">
    <div class="grid overflow-hidden rounded-4xl shadow-lift ring-1 ring-black/[.05] md:grid-cols-2">

        {{-- Panel brand.
             Di mobile disembunyikan: isinya ~450px dokumentasi, sedangkan
             tujuan utama pengguna di halaman ini adalah mengisi form. --}}
        <div class="auth-brand relative hidden overflow-hidden bg-brand-panel p-10 text-white md:block md:p-14">
            <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full accent-haze blur-3xl" aria-hidden="true"></div>
            <div class="relative">
                <div class="film-line w-28 opacity-50" aria-hidden="true"></div>
                <p class="py-2 font-serif text-lg font-extrabold tracking-[.18em]">HOLD' MOMENT</p>
                <div class="film-line w-28 opacity-50" aria-hidden="true"></div>

                <h2 class="mt-10 text-balance font-serif text-3xl font-extrabold leading-tight md:text-4xl">
                    {{ $heading }}
                </h2>

                @if (count($points))
                    <ul class="mt-9 space-y-3.5">
                        @foreach ($points as $point)
                            <li class="flex items-start gap-3 text-sm text-white/80">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-orange text-white" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                                        <path d="M5 12.5l4.5 4.5L19 7.5"/>
                                    </svg>
                                </span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Panel form --}}
        <div class="bg-white p-10 md:p-14">
            <h2 class="font-serif text-3xl font-extrabold">{{ $title }}</h2>
            <p class="mt-2 text-sm text-brand-muted">{{ $subtitle }}</p>

            @if ($errors->any())
                <div role="alert" class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <p class="font-semibold">Periksa kembali isianmu:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>