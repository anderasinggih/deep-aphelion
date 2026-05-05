@props([
'user' => null,
'size' => 'w-9 h-9',
'class' => ''
])

@php
    $initials = '??';
    if ($user && $user->name) {
        $nameParts = explode(' ', $user->name);
        $initials = collect($nameParts)->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
    }
@endphp

<div class="avatar placeholder {{ $class }}">
    <div class="bg-primary text-primary-content rounded-full {{ $size }} shadow-sm flex items-center justify-center">
        <span class="text-[10px] font-black tracking-tighter">{{ strtoupper($initials) }}</span>
    </div>
</div>