@extends('layouts.app')
@section('title', 'Create Tourist Account | JoePro')
@section('content')
<section class="mx-auto grid min-h-[72vh] max-w-6xl items-center gap-10 px-5 py-14 lg:grid-cols-[1fr_.85fr] lg:py-20">
    <div class="hidden lg:block">
        <p class="eyebrow text-[#65776e]">START YOUR ADVENTURE</p>
        <h1 class="mt-3 max-w-xl font-serif text-6xl leading-[1.02]">Your next canyon experience starts here.</h1>
        <p class="mt-6 max-w-lg text-lg leading-8 text-black/60">Create your tourist account to reserve packages, keep track of booking decisions, and manage your upcoming trips.</p>
        <div class="mt-8 grid max-w-lg grid-cols-3 gap-3 text-center text-xs font-bold">
            <div class="rounded-2xl bg-white/65 p-4">Browse packages</div>
            <div class="rounded-2xl bg-white/65 p-4">Book online</div>
            <div class="rounded-2xl bg-white/65 p-4">Track status</div>
        </div>
    </div>

    <div class="w-full rounded-3xl border border-black/10 bg-white/85 p-7 shadow-xl sm:p-9">
        <div class="flex items-center gap-4">
            <img src="{{ asset('joepro-logo.png') }}" class="size-16 rounded-full" alt="JoePro logo">
            <div>
                <p class="eyebrow text-[#65776e]">TOURIST REGISTRATION</p>
                <h2 class="font-serif text-3xl">Create your account</h2>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-[#315c4b]/15 bg-[#edf3ee] px-4 py-3 text-sm text-[#315c4b]">
            Public registration creates tourist accounts only. Operator access is issued privately by JoePro management.
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="mt-6 grid gap-4">
            @csrf
            <label class="text-sm font-bold">Full name
                <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none transition focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="text" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Juan Dela Cruz" required autofocus>
                @error('name')<span class="mt-1 block text-xs font-medium text-red-700">{{ $message }}</span>@enderror
            </label>
            <label class="text-sm font-bold">Email address
                <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none transition focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required>
                @error('email')<span class="mt-1 block text-xs font-medium text-red-700">{{ $message }}</span>@enderror
            </label>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-sm font-bold">Password
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none transition focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="password" name="password" autocomplete="new-password" placeholder="At least 8 characters" required>
                    @error('password')<span class="mt-1 block text-xs font-medium text-red-700">{{ $message }}</span>@enderror
                </label>
                <label class="text-sm font-bold">Confirm password
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none transition focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repeat password" required>
                </label>
            </div>
            <button class="mt-2 rounded-xl bg-[#14221c] px-5 py-4 font-bold text-white transition hover:bg-[#245442]">Create tourist account</button>
            <p class="text-center text-sm text-black/55">Already registered? <a href="{{ route('login') }}" class="font-bold text-[#315c4b] hover:underline">Sign in</a></p>
        </form>
    </div>
</section>
@endsection
