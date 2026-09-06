<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperatorController extends Controller
{
    private function check(Request $request): void
    {
        abort_if($request->user()->role !== 'operator', 403);
    }

    public function index(Request $request): View
    {
        $this->check($request);
        $bookings = Booking::with(['package', 'user'])->latest()->get();

        return view('operator.index', ['bookings' => $bookings, 'pending' => $bookings->where('status', 'pending')->count(), 'approved' => $bookings->where('status', 'approved')->count(), 'revenue' => $bookings->where('status', 'approved')->sum('total_amount')]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $this->check($request);
        $data = $request->validate(['status' => ['required', 'in:approved,rejected']]);
        $booking->update($data);

        return back()->with('success', 'Booking '.$data['status'].'.');
    }
}
