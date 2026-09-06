@extends('layouts.app')
@section('title', 'Packages | JoePro')
@section('content')
<section class="mx-auto min-h-[70vh] max-w-6xl px-5 py-12 lg:py-16">
    <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
        <div>
            <p class="eyebrow text-[#65776e]">EXPLORE JOEPRO</p>
            <h1 class="font-serif text-5xl tracking-tight sm:text-6xl">Choose your adventure</h1>
            <p class="mt-4 max-w-2xl leading-7 text-black/55">Compare experiences, select your preferred date, and send a booking request. The JoePro team will review and confirm availability.</p>
        </div>
        @auth
            @if(auth()->user()->role === 'tourist')
                <a href="{{ route('bookings.index') }}" class="rounded-full border border-black/15 bg-white/70 px-5 py-3 text-center text-sm font-bold">Track my bookings →</a>
            @endif
        @endauth
    </div>

    @if($errors->any())
        <div class="mt-7 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">{{ $errors->first() }}</div>
    @endif

    <div class="mt-10 grid gap-8">
        @foreach($packages as $package)
            <article id="package-{{ $package->id }}" class="overflow-hidden rounded-3xl border border-black/10 bg-white/70 shadow-sm">
                <div class="grid lg:grid-cols-[.8fr_1.2fr]">
                    <div class="relative min-h-72 bg-cover bg-center lg:min-h-full" style="background-image:url('{{ $package->image_url }}')">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-6 pt-20 text-white lg:hidden"><p class="text-xs font-bold tracking-widest">{{ $package->location }}</p></div>
                    </div>
                    <div class="p-6 sm:p-9">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="rounded-full bg-[#d8ee75] px-3 py-2 text-xs font-bold">{{ $package->difficulty }}</span>
                            <span class="text-xs font-bold text-black/45">{{ $package->duration }} · Up to {{ $package->capacity }} guests</span>
                        </div>
                        <h2 class="mt-5 font-serif text-4xl">{{ $package->name }}</h2>
                        <p class="mt-1 text-sm font-bold text-[#315c4b]">{{ $package->location }}</p>
                        <p class="mt-4 text-sm leading-6 text-black/55">{{ $package->description }}</p>
                        <div class="my-6 grid gap-3 text-sm sm:grid-cols-2">
                            <span>✓ Eco entrance fee</span><span>✓ Transport to canyon start</span>
                            <span>✓ Helmet, vest & water shoes</span><span>✓ Lunch, fruit & drinks</span>
                        </div>
                        <div class="flex flex-wrap items-end justify-between gap-5 border-t border-black/10 pt-6">
                            <p><small class="block tracking-widest text-black/45">PER PERSON</small><strong class="font-serif text-3xl">₱{{ number_format($package->price) }}</strong></p>
                            @auth
                                @if(auth()->user()->role === 'tourist')
                                    <button type="button" data-booking-toggle="booking-{{ $package->id }}" class="rounded-full bg-[#14221c] px-7 py-3.5 text-sm font-bold text-white transition hover:bg-[#245442]">Book this package</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="rounded-full bg-[#14221c] px-7 py-3.5 text-sm font-bold text-white">Login to book</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @auth
                    @if(auth()->user()->role === 'tourist')
                        <div id="booking-{{ $package->id }}" class="{{ $errors->any() && old('package_id') == $package->id ? '' : 'hidden' }} border-t border-black/10 bg-[#e8eadf] p-6 sm:p-8">
                            <div class="mb-6 flex items-start justify-between gap-5">
                                <div><p class="eyebrow text-[#65776e]">BOOKING REQUEST</p><h3 class="font-serif text-2xl">Plan your {{ $package->name }}</h3><p class="mt-1 text-xs text-black/50">No payment is collected yet. The operator will confirm your request first.</p></div>
                                <button type="button" data-booking-close="booking-{{ $package->id }}" class="rounded-full bg-white/70 px-3 py-2 text-xs font-bold">Close</button>
                            </div>
                            <form class="booking-form grid gap-5 lg:grid-cols-3" method="POST" action="{{ route('bookings.store') }}" data-price="{{ $package->price }}">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <label class="text-xs font-bold">Preferred tour date
                                    @if($package->available_dates)
                                        <select class="tour-date mt-2 w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm" name="tour_date" required>
                                            <option value="">Select an available date</option>
                                            @foreach($package->available_dates as $availableDate)
                                                @php($reserved = $package->bookings->filter(fn ($booking) => $booking->tour_date->toDateString() === $availableDate)->sum('guest_count'))
                                                @php($remaining = max(0, $package->capacity - $reserved))
                                                <option value="{{ $availableDate }}" data-remaining="{{ $remaining }}" @disabled($remaining === 0) @selected(old('package_id') == $package->id && old('tour_date') === $availableDate)>{{ \Carbon\Carbon::parse($availableDate)->format('D, M j, Y') }} — {{ $remaining ? $remaining.' slots left' : 'Fully booked' }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input class="tour-date mt-2 w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm" type="date" name="tour_date" min="{{ now()->addDay()->toDateString() }}" value="{{ old('package_id') == $package->id ? old('tour_date') : '' }}" required>
                                    @endif
                                </label>
                                <label class="text-xs font-bold">Number of guests
                                    <select class="guest-count mt-2 w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm" name="guest_count" required>
                                        @for($i = 1; $i <= $package->capacity; $i++)
                                            <option value="{{ $i }}" @selected(old('package_id') == $package->id && old('guest_count') == $i)>{{ $i }} {{ Str::plural('guest', $i) }}</option>
                                        @endfor
                                    </select>
                                </label>
                                <label class="text-xs font-bold">Contact number
                                    <input class="mt-2 w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm" type="tel" name="contact_number" value="{{ old('package_id') == $package->id ? old('contact_number') : '' }}" placeholder="09XX XXX XXXX" autocomplete="tel" required>
                                </label>
                                <label class="text-xs font-bold lg:col-span-2">Special requests <span class="font-normal text-black/40">(optional)</span>
                                    <textarea class="mt-2 w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm" name="special_requests" rows="2" placeholder="Pickup details, accessibility needs, or other notes">{{ old('package_id') == $package->id ? old('special_requests') : '' }}</textarea>
                                </label>
                                <div class="flex items-center justify-between gap-4 rounded-2xl bg-[#14221c] px-5 py-4 text-white">
                                    <p><small class="block text-[10px] tracking-widest text-white/50">ESTIMATED TOTAL</small><strong class="booking-total font-serif text-2xl">₱{{ number_format($package->price) }}</strong></p>
                                    <button class="rounded-xl bg-[#d8ee75] px-4 py-3 text-xs font-black text-[#14221c]">Send request</button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endauth
            </article>
        @endforeach
    </div>
</section>
@endsection
