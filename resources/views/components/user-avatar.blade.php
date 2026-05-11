@props([
'user' => null,
'size' => 'w-9 h-9',
'class' => '',
'initials' => null
])

@php
    $displayInitials = $initials ?? '??';
    if ($user && $user->name) {
        $nameParts = explode(' ', $user->name);
        $displayInitials = collect($nameParts)->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
    }
@endphp

<div class="avatar placeholder {{ $class }}">
    <div class="bg-primary text-primary-content rounded-full {{ $size }} shadow-sm flex items-center justify-center overflow-hidden">
        <span class="font-black tracking-tighter text-[0.7rem] sm:text-xs">{{ strtoupper($displayInitials) }}</span>
    </div>
</div>