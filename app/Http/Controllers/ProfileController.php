<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $this->authorizeTourist($request);

        return view('profile.edit');
    }

    public function updateInformation(Request $request): RedirectResponse
    {
        $this->authorizeTourist($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users')->ignore($request->user())],
        ]);

        $request->user()->update([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
        ]);

        return back()->with('success', 'Profile information updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $this->authorizeTourist($request);

        $data = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password changed successfully.');
    }

    private function authorizeTourist(Request $request): void
    {
        abort_if($request->user()->role !== 'tourist', 403);
    }
}
