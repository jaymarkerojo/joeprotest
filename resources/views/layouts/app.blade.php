<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JoePro Adventures')</title>
    <link rel="icon" href="{{ asset('joepro-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f1e8] text-[#14221c] antialiased">
<header class="mx-auto flex min-h-20 max-w-[1440px] items-center justify-between gap-6 px-5 lg:px-16">
    <a href="{{ route('home') }}" class="flex items-center gap-3 font-black tracking-[.12em]"><img src="{{ asset('joepro-logo.png') }}" class="size-11 rounded-full" alt="JoePro logo"><span class="hidden sm:block">JOEPRO ADVENTURES</span></a>
    <nav class="flex items-center gap-3 text-sm font-semibold sm:gap-7">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
        <a class="nav-link {{ request()->routeIs('packages.index') ? 'active' : '' }}" href="{{ route('packages.index') }}">Packages</a>
        @auth
            @if(auth()->user()->role === 'operator')
                <a class="nav-link {{ request()->routeIs('operator.index') ? 'active' : '' }}" href="{{ route('operator.index') }}">Bookings</a>
                <a class="nav-link {{ request()->routeIs('operator.packages.*') ? 'active' : '' }}" href="{{ route('operator.packages.index') }}">Manage packages</a>
            @else
                <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">My bookings</a>
                <details class="group relative">
                    <summary aria-label="Open profile menu" class="grid size-11 cursor-pointer list-none place-items-center rounded-full border border-black/10 bg-[#315c4b] text-sm font-black text-white transition hover:bg-[#244638]">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </summary>
                    <div class="absolute right-0 z-40 mt-2 w-56 overflow-hidden rounded-2xl border border-black/10 bg-white p-2 shadow-2xl">
                        <div class="border-b border-black/10 px-3 py-2.5">
                            <p class="truncate text-sm font-bold">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs font-normal text-black/45">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm hover:bg-[#edf3ee]">Profile settings</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full rounded-xl px-3 py-2.5 text-left text-sm text-red-700 hover:bg-red-50">Log out</button></form>
                    </div>
                </details>
            @endif
            @if(auth()->user()->role === 'operator')
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="rounded-full border border-black/15 bg-white/60 px-4 py-2">Logout</button></form>
            @endif
        @else
            <a class="nav-link hidden sm:block {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
            <a href="{{ route('login') }}" class="rounded-full bg-[#14221c] px-4 py-2 text-white">Login</a>
        @endauth
    </nav>
</header>
@if(session('success'))<div class="fixed left-1/2 top-24 z-50 -translate-x-1/2 rounded-xl bg-[#14221c] px-5 py-3 text-sm text-white shadow-2xl">{{ session('success') }}</div>@endif
<main>@yield('content')</main>
<footer class="grid gap-10 bg-[#14221c] px-[max(5vw,calc((100vw-1320px)/2))] py-14 text-white md:grid-cols-3"><div><h3 class="font-serif text-xl">JoePro Canyoneering Adventures</h3><p class="mt-3 max-w-md text-sm leading-6 text-white/65">Experience the wild beauty of Southern Cebu with local guides who put safety first.</p></div><div class="flex flex-col gap-2 text-sm text-white/65"><strong class="font-serif text-lg text-white">Book with us</strong><a href="tel:+639355314072">0935 531 4072</a><a href="https://wa.me/639458805357">WhatsApp: +63 945 880 5357</a></div><div class="flex flex-col gap-2 text-sm text-white/65"><strong class="font-serif text-lg text-white">Find us</strong><span>Badian, Cebu, Philippines</span><span>Always open • Online booking</span></div></footer>
</body>
</html>
