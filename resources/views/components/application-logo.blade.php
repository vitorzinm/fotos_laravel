@php
$img = auth()->user()?->profile_img;
$src = $img ? asset('storage/' . $img) : asset('images/pfp.webp');
@endphp 

<span {{ $attributes->merge(['class' => 'inline-flex h-40 w-40 items-center justify-center overflow-hidden rounded-full bg-zinc-800']) }}>
    <img src="{{ $src }}" alt="Foto de perfil" class="h-full w-full rounded-full object-cover">
</span>

