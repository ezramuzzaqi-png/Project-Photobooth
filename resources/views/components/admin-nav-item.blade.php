@props(['href' => '#', 'active' => false, 'label' => ''])
<a href="{{ $href }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition whitespace-nowrap {{ $active ? 'bg-brand-orange text-white shadow-sm' : 'text-brand-text hover:bg-brand-card-light' }}">
    {{ $slot }}
    <span>{{ $label }}</span>
</a>
