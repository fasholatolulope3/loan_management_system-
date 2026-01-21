@props(['icon', 'label', 'active' => false])

<div x-data="{ open: @js($active) }" class="space-y-1">
    <button @click="open = !open" :class="collapsed ? 'justify-center' : 'justify-between'"
        class="w-full flex items-center px-4 py-2.5 text-sm font-medium text-slate-400 rounded-lg hover:bg-slate-800 hover:text-white transition-all group">
        <div class="flex items-center">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 mr-3 group-hover:text-white" />
            <span x-show="!collapsed">{{ $label }}</span>
        </div>
        <svg x-show="!collapsed" :class="open ? 'rotate-90' : ''" class="w-4 h-4 transition-transform" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <div x-show="open && !collapsed" x-cloak class="pl-10 space-y-1">
        {{ $slot }}
    </div>
</div>
