@props([
    'user' => null,
])

@php
    $showAvatar = true;
    $showName = true;
@endphp

<div class="flex items-center gap-2">
    @if ($showAvatar && $user)
        <img src="{{ $user->profile_photo_url }}"
             class="w-8 h-8 rounded-full object-cover"
             alt="Profilbild">
    @else
        <img src="{{ asset('site-images/defaultuser.jpg') }}"
             class="w-8 h-8 rounded-full object-cover"
             alt="Default User bild">
    @endif
    <span class="text-sm font-medium text-gray-800">
        {{ $showName ? $user->name : 'Anonym' }}
    </span>
</div>