@extends('layouts.app')
@section('title', 'Sign In | JoePro')
@section('content')
<section class="mx-auto grid min-h-[72vh] max-w-5xl place-items-center px-5 py-16">
    <div class="w-full max-w-md rounded-3xl border border-black/10 bg-white/80 p-8 shadow-xl">
        <img src="{{ asset('joepro-logo.png') }}" class="mx-auto size-20 rounded-full" alt="JoePro logo">
        <div class="mt-5 text-center">
            <p class="eyebrow text-[#65776e]">WELCOME TO JOEPRO</p>
            <h1 class="font-serif text-4xl">Sign in to your account</h1>
            <p class="mt-2 text-sm text-black/50">Manage your adventures and booking updates.</p>
        </div>
        <form method="POST" action="{{ route('login.store') }}" class="mt-7 grid gap-4">
            @csrf
            <label class="text-sm font-bold">Email address
                <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none transition focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required autofocus>
            </label>
            <label class="text-sm font-bold">Password
                <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none transition focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required>
            </label>
            <label class="flex items-center gap-2 text-xs font-medium text-black/55"><input type="checkbox" name="remember" value="1" class="size-4 rounded"> Keep me signed in</label>
            @error('email')<p class="rounded-lg bg-red-100 p-3 text-xs text-red-800">{{ $message }}</p>@enderror
            <button class="rounded-xl bg-[#14221c] px-5 py-4 font-bold text-white transition hover:bg-[#245442]">Sign in</button>
            <p class="text-center text-sm text-black/55">New to JoePro? <a href="{{ route('register') }}" class="font-bold text-[#315c4b] hover:underline">Create a tourist account</a></p>
        </form>
    </div>
</section>
@endsection
