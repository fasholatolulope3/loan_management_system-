@props(['icon' => null, 'route', 'active' => false, 'subItem' => false])

@php
    $classes = $active ?? false ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white';

    $finalClasses =
        'flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group ' . $classes;
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge(['class' => $finalClasses]) }}>
    @if ($icon)
        <x-dynamic-component :component="'heroicon-o-' . $icon"
            class="w-5 h-5 mr-3 transition-colors {{ $active ? 'text-white' : 'group-hover:text-white' }}" />
    @endif

    <span x-show="!collapsed" class="truncate">{{ $slot }}</span>

    <!-- Tooltip for collapsed mode -->
    <span x-show="collapsed"
        class="absolute left-16 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
        {{ $slot }}
    </span>
</a>
