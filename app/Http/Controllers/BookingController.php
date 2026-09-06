<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $r): View
    {
        abort_if($r->user()->role !== 'tourist', 403);

        return view('bookings.index', ['bookings' => $r->user()->bookings()->with('package')->latest()->get()]);
    }

    public function store(Request $r): RedirectResponse
    {
        abort_if($r->user()->role !== 'tourist', 403);
        $d = $r->validate(['package_id' => ['required', Rule::exists('packages', 'id')->where('is_available', true)], 'tour_date' => ['required', 'date', 'after:today'], 'guest_count' => ['required', 'integer', 'min:1', 'max:100'], 'contact_number' => ['required', 'string', 'min:7', 'max:30'], 'special_requests' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($d, $r) {
            $p = Package::lockForUpdate()->findOrFail($d['package_id']);
            if ($p->available_dates && ! in_array($d['tour_date'], $p->available_dates, true)) {
                throw ValidationException::withMessages(['tour_date' => 'Please select one of the available dates provided by the operator.']);
            }
            $reserved = Booking::where('package_id', $p->id)->whereDate('tour_date', $d['tour_date'])->whereIn('status', ['pending', 'approved'])->sum('guest_count');
            if ($reserved + $d['guest_count'] > $p->capacity) {
                throw ValidationException::withMessages(['tour_date' => 'This date does not have enough remaining slots. Please reduce the guests or select another date.']);
            }
            Booking::create(['reference_no' => 'JP-'.now()->year.'-'.strtoupper(Str::random(5)), 'package_id' => $p->id, 'user_id' => $r->user()->id, 'contact_number' => $d['contact_number'] ?? null, 'tour_date' => $d['tour_date'], 'guest_count' => $d['guest_count'], 'special_requests' => $d['special_requests'] ?? null, 'total_amount' => $p->price * $d['guest_count'], 'status' => 'pending']);
        });

        return redirect()->route('bookings.index')->with('success', 'Your booking request has been submitted.');
    }

    public function cancel(Request $r, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $r->user()->id && $booking->status === 'pending', 403);
        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking request cancelled.');
    }
}
