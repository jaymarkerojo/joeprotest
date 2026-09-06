@extends('layouts.app')
@section('title', 'Profile Settings | JoePro')
@section('content')
<section class="mx-auto min-h-[72vh] max-w-6xl px-5 py-12 lg:py-16">
    <div class="mb-9 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="eyebrow text-[#65776e]">MY JOEPRO ACCOUNT</p>
            <h1 class="font-serif text-4xl sm:text-5xl">Profile settings</h1>
            <p class="mt-3 max-w-xl text-black/55">Keep your account details accurate and your password secure.</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="text-sm font-bold text-[#315c4b] hover:underline">View my bookings →</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('profile.information.update') }}" class="rounded-3xl border border-black/10 bg-white/75 p-7 shadow-sm sm:p-8">
            @csrf
            @method('PUT')
            <div class="flex items-center gap-4 border-b border-black/10 pb-5">
                <span class="grid size-14 place-items-center rounded-full bg-[#14221c] text-xl font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div><h2 class="font-serif text-2xl">Personal information</h2><p class="text-xs text-black/45">Used for your booking records</p></div>
            </div>
            <div class="mt-6 grid gap-5">
                <label class="text-sm font-bold">Full name
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required>
                    @error('name')<span class="mt-1 block text-xs text-red-700">{{ $message }}</span>@enderror
                </label>
                <label class="text-sm font-bold">Email address
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required>
                    @error('email')<span class="mt-1 block text-xs text-red-700">{{ $message }}</span>@enderror
                </label>
                <button class="rounded-xl bg-[#14221c] px-5 py-3.5 font-bold text-white transition hover:bg-[#245442]">Save profile</button>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.password.update') }}" class="rounded-3xl border border-black/10 bg-white/75 p-7 shadow-sm sm:p-8">
            @csrf
            @method('PUT')
            <div class="border-b border-black/10 pb-5">
                <h2 class="font-serif text-2xl">Change password</h2>
                <p class="mt-1 text-xs text-black/45">Confirm your current password before choosing a new one.</p>
            </div>
            <div class="mt-6 grid gap-5">
                <label class="text-sm font-bold">Current password
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="password" name="current_password" autocomplete="current-password" required>
                    @error('current_password', 'passwordUpdate')<span class="mt-1 block text-xs text-red-700">{{ $message }}</span>@enderror
                </label>
                <label class="text-sm font-bold">New password
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="password" name="password" autocomplete="new-password" placeholder="At least 8 characters" required>
                    @error('password', 'passwordUpdate')<span class="mt-1 block text-xs text-red-700">{{ $message }}</span>@enderror
                </label>
                <label class="text-sm font-bold">Confirm new password
                    <input class="mt-2 w-full rounded-xl border border-black/15 bg-white px-4 py-3 font-normal outline-none focus:border-[#315c4b] focus:ring-2 focus:ring-[#315c4b]/15" type="password" name="password_confirmation" autocomplete="new-password" required>
                </label>
                <button class="rounded-xl bg-[#315c4b] px-5 py-3.5 font-bold text-white transition hover:bg-[#244638]">Update password</button>
            </div>
        </form>
    </div>
</section>
@endsection
