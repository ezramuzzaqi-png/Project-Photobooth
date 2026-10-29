@props(['href' => '#', 'active' => false, 'label' => ''])
<a href="{{ $href }}"
   @if($active) aria-current="page" @endif
   class="flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 whitespace-nowrap {{ $active ? 'bg-brand-orange text-white shadow-glow' : 'text-brand-muted hover:bg-brand-card-light hover:text-brand-text' }}">
    <span class="shrink-0" aria-hidden="true">{{ $slot }}</span>
    <span>{{ $label }}</span>
</a>