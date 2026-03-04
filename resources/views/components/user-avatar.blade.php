@props([
'user' => null,
'size' => 'w-9 h-9',
'class' => ''
])

<div class="avatar shadow-sm {{ $class }}">
    <div class="{{ $size }} rounded-full">
        @if($user)
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" />
        @else
        <div class="flex items-center justify-center h-full w-full bg-base-300 text-base-content/40">
            <x-icon name="o-user" class="w-1/2 h-1/2" />
        </div>
        @endif
    </div>
</div>