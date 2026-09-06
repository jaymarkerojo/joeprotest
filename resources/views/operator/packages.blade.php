@extends('layouts.app')
@section('title', 'Manage Packages | JoePro')
@section('content')
<section class="mx-auto min-h-[72vh] max-w-7xl px-5 py-16">
    <div class="flex flex-wrap items-end justify-between gap-5">
        <div><p class="eyebrow text-[#65776e]">JOEPRO OPERATIONS</p><h1 class="font-serif text-5xl sm:text-6xl">Manage packages</h1><p class="mt-3 text-black/55">Add the basic trip details, upload a photo, and choose the dates tourists can book.</p></div>
        <a href="{{ route('operator.index') }}" class="rounded-full border border-black/15 bg-white/60 px-5 py-3 text-sm font-bold">← Booking desk</a>
    </div>

    @if($errors->any())<div class="mt-7 rounded-xl bg-red-100 p-4 text-sm text-red-800"><strong>Please review the form:</strong> {{ $errors->first() }}</div>@endif

    <div class="mt-10 grid gap-6 lg:grid-cols-[380px_1fr]">
        <aside class="h-max rounded-3xl bg-[#183f32] p-7 text-white shadow-xl lg:sticky lg:top-6">
            <p class="eyebrow text-white/55">NEW PACKAGE</p><h2 class="font-serif text-3xl">Create an adventure</h2>
            <form method="POST" action="{{ route('operator.packages.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-4">@csrf
                <label class="field-label">Package name<input name="name" value="{{ old('name') }}" required placeholder="e.g. Sunrise Canyon Tour"></label>
                <label class="field-label">Location<input name="location" value="{{ old('location') }}" required placeholder="Badian, Cebu"></label>
                <label class="field-label">Description<textarea name="description" rows="3" required placeholder="Describe the experience and inclusions.">{{ old('description') }}</textarea></label>
                <div class="grid grid-cols-2 gap-3"><label class="field-label">Price (₱)<input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required></label><label class="field-label">Capacity<input name="capacity" type="number" min="1" max="100" value="{{ old('capacity', 10) }}" required></label></div>
                <div class="grid grid-cols-2 gap-3"><label class="field-label">Duration<input name="duration" value="{{ old('duration') }}" placeholder="4-5 hours" required></label><label class="field-label">Category<input name="difficulty" value="{{ old('difficulty') }}" placeholder="All levels" required></label></div>
                <label class="field-label">Package photo<input name="image" type="file" accept="image/png,image/jpeg,image/webp" required></label>
                <fieldset class="grid gap-2"><legend class="mb-2 text-xs font-bold text-white/75">Available dates <span class="font-normal">(up to 5)</span></legend>
                    @for($i = 0; $i < 5; $i++)
                        <input class="rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm text-white [color-scheme:dark]" name="available_dates[]" type="date" min="{{ now()->addDay()->toDateString() }}" value="{{ old('available_dates.'.$i) }}">
                    @endfor
                </fieldset>
                <label class="flex items-center gap-2 text-xs text-white/75"><input type="checkbox" name="is_available" value="1" checked> Publish immediately</label>
                <button class="rounded-xl bg-[#d8ee75] px-5 py-4 font-bold text-[#14221c] transition hover:bg-white">Create package</button>
            </form>
        </aside>

        <div class="grid gap-5">
            @forelse($packages as $package)
                <article class="overflow-hidden rounded-3xl border border-black/10 bg-white/70">
                    <div class="grid md:grid-cols-[190px_1fr]"><div class="min-h-48 bg-cover bg-center" style="background-image:url('{{ $package->image_url }}')"></div>
                        <form method="POST" action="{{ route('operator.packages.update', $package) }}" enctype="multipart/form-data" class="grid gap-4 p-6">@csrf @method('PUT')
                            <div class="flex items-center justify-between gap-4"><div><span class="status {{ $package->is_available ? 'status-approved' : 'status-cancelled' }}">{{ $package->is_available ? 'Published' : 'Archived' }}</span><span class="ml-2 text-xs text-black/45">{{ $package->bookings_count }} bookings</span></div><label class="flex items-center gap-2 text-xs font-bold"><input type="checkbox" name="is_available" value="1" @checked($package->is_available)> Available</label></div>
                            <div class="grid gap-3 sm:grid-cols-2"><label class="manage-label">Package name<input name="name" value="{{ $package->name }}" required></label><label class="manage-label">Location<input name="location" value="{{ $package->location }}" required></label></div>
                            <label class="manage-label">Description<textarea name="description" rows="2" required>{{ $package->description }}</textarea></label>
                            <div class="grid gap-3 sm:grid-cols-4"><label class="manage-label">Price<input name="price" type="number" min="0" step=".01" value="{{ $package->price }}" required></label><label class="manage-label">Capacity<input name="capacity" type="number" min="1" value="{{ $package->capacity }}" required></label><label class="manage-label">Duration<input name="duration" value="{{ $package->duration }}" required></label><label class="manage-label">Category<input name="difficulty" value="{{ $package->difficulty }}" required></label></div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="manage-label">Replace photo <span class="font-normal text-black/40">(optional)</span><input name="image" type="file" accept="image/png,image/jpeg,image/webp"></label>
                                <fieldset><legend class="mb-2 text-xs font-bold">Available dates</legend><div class="grid gap-2 sm:grid-cols-2">
                                    @for($i = 0; $i < 5; $i++)
                                        <input class="rounded-lg border border-black/10 bg-white px-3 py-2 text-xs" name="available_dates[]" type="date" min="{{ now()->addDay()->toDateString() }}" value="{{ $package->available_dates[$i] ?? '' }}">
                                    @endfor
                                </div></fieldset>
                            </div>
                            <div class="flex justify-end"><button class="rounded-xl bg-[#14221c] px-5 py-3 text-sm font-bold text-white">Save package</button></div>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-black/20 p-14 text-center"><h2 class="font-serif text-2xl">No packages yet</h2><p class="mt-2 text-sm text-black/50">Create your first adventure using the form.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
