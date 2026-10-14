{{-- Pustaka ikon SVG garis (gaya svgrepo/line): stroke = warna teks induk.
     Pakai: <x-icon name="check" class="w-5 h-5" /> --}}
@props(['name' => 'check', 'class' => 'w-5 h-5'])
@php
$icons = [
    'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
    'print' => '<path d="M7 8V4h10v4"/><path d="M4 8h16a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-3m-10 0H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/><rect x="7" y="14" width="10" height="7" rx="1"/>',
    'download' => '<path d="M12 4v11m0 0l-4.5-4.5M12 15l4.5-4.5M4.5 19.5h15"/>',
    'share' => '<path d="M7 17L17 7M9 7h8v8"/>',
    'camera' => '<path d="M4 8h2.6L8.6 5.5h6.8L17.4 8H20A1.5 1.5 0 0 1 21.5 9.5V18a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 18V9.5A1.5 1.5 0 0 1 4 8z"/><circle cx="12" cy="13.5" r="3.4"/>',
    'grid' => '<rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/>',
    'sliders' => '<path d="M4 8h8M18 8h2M4 16h2M12 16h8"/><circle cx="15" cy="8" r="2.2"/><circle cx="9" cy="16" r="2.2"/>',
    'clock' => '<circle cx="12" cy="13" r="7.5"/><path d="M12 9.5V13l2.5 1.5M9.5 3h5"/>',
    'refresh' => '<path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.5 9a9 9 0 0 1 14.9-3.4L23 10M1 14l4.6 4.4A9 9 0 0 0 20.5 15"/>',
    'arrow-left' => '<path d="M19 12H5m0 0l6-6m-6 6l6 6"/>',
    'arrow-right' => '<path d="M5 12h14m0 0l-6-6m6 6l-6 6"/>',
    'chevron-left' => '<path d="M14.5 6L8.5 12l6 6"/>',
    'spark' => '<path d="M12 3.5l2 6.5 6.5 2-6.5 2-2 6.5-2-6.5-6.5-2 6.5-2z"/>',
    'bolt' => '<path d="M13 2.5L4.5 13.5H11l-1 8 8.5-11H12z"/>',
    'star' => '<path fill="currentColor" stroke="none" d="M12 2.6l2.9 5.9 6.5.9-4.7 4.6 1.1 6.5-5.8-3-5.8 3 1.1-6.5L2.6 9.4l6.5-.9z"/>',
    'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
    'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/>',
    'link' => '<path d="M10 13.5a4.5 4.5 0 0 0 6.4.4l3-3a4.5 4.5 0 0 0-6.4-6.4l-1.7 1.7M14 10.5a4.5 4.5 0 0 0-6.4-.4l-3 3a4.5 4.5 0 0 0 6.4 6.4l1.7-1.7"/>',
    'user' => '<circle cx="12" cy="8" r="3.6"/><path d="M5 20c.8-3.4 3.6-5 7-5s6.2 1.6 7 5"/>',
    'pencil' => '<path d="M4 20l1-4L16.5 4.5a2.1 2.1 0 0 1 3 3L8 19z"/><path d="M14.5 6.5l3 3"/>',
    'image' => '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="M4.5 17.5l4.5-4.5 3 3 3.5-3.5 4 4"/>',
    'receipt' => '<path d="M5 3.5h14V21l-2.3-1.5-2.3 1.5-2.4-1.5-2.4 1.5L7.3 19.5 5 21z"/><path d="M9 8.5h6M9 12h6"/>',
];
@endphp
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $class }}" aria-hidden="true">{!! $icons[$name] ?? '' !!}</svg>
