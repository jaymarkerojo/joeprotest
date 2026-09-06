<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingModulesTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_guest_can_register_only_as_a_tourist(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Tourist',
            'email' => 'newtourist@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'role' => 'operator',
        ]);

        $user = User::where('email', 'newtourist@example.com')->firstOrFail();

        $response->assertRedirect(route('packages.index'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('tourist', $user->role);
        $this->assertTrue(Hash::check('SecurePass123!', $user->password));
        $this->assertDatabaseHas('users', [
            'email' => 'newtourist@example.com',
            'role' => 'tourist',
        ]);
    }

    public function test_tourist_can_change_password_with_their_current_password(): void
    {
        $tourist = User::factory()->create([
            'role' => 'tourist',
            'password' => Hash::make('CurrentPass123!'),
        ]);

        $response = $this->actingAs($tourist)->put(route('profile.password.update'), [
            'current_password' => 'CurrentPass123!',
            'password' => 'NewSecurePass456!',
            'password_confirmation' => 'NewSecurePass456!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewSecurePass456!', $tourist->fresh()->password));
    }

    public function test_tourist_cannot_change_password_using_a_wrong_current_password(): void
    {
        $tourist = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($tourist)->from(route('profile.edit'))->put(route('profile.password.update'), [
            'current_password' => 'NotTheirPassword!',
            'password' => 'NewSecurePass456!',
            'password_confirmation' => 'NewSecurePass456!',
        ])->assertRedirect(route('profile.edit'))->assertSessionHasErrors('current_password', errorBag: 'passwordUpdate');
    }

    public function test_tourist_can_create_a_booking(): void
    {
        $tourist = User::where('role', 'tourist')->firstOrFail();
        $package = Package::firstOrFail();

        $response = $this->actingAs($tourist)->post(route('bookings.store'), [
            'package_id' => $package->id,
            'tour_date' => now()->addWeek()->toDateString(),
            'guest_count' => 3,
            'contact_number' => '09123456789',
            'special_requests' => 'First-time canyoneer.',
        ]);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $tourist->id,
            'package_id' => $package->id,
            'guest_count' => 3,
            'status' => 'pending',
        ]);
    }

    public function test_operator_can_approve_a_pending_booking(): void
    {
        $operator = User::where('role', 'operator')->firstOrFail();
        $booking = Booking::where('status', 'pending')->firstOrFail();

        $response = $this->actingAs($operator)->patch(route('operator.bookings.update', $booking), [
            'status' => 'approved',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'approved']);
    }

    public function test_operator_can_create_and_update_a_package(): void
    {
        Storage::fake('public');
        $operator = User::where('role', 'operator')->firstOrFail();

        $this->actingAs($operator)->post(route('operator.packages.store'), [
            'name' => 'Sunrise Canyon Tour',
            'location' => 'Badian, Cebu',
            'description' => 'An early guided canyon experience.',
            'price' => 2800,
            'duration' => '5 hours',
            'difficulty' => 'Intermediate',
            'capacity' => 8,
            'image' => UploadedFile::fake()->image('canyon.jpg'),
            'available_dates' => [now()->addDays(10)->toDateString()],
            'is_available' => 1,
        ])->assertRedirect();

        $package = Package::where('name', 'Sunrise Canyon Tour')->firstOrFail();
        $this->actingAs($operator)->put(route('operator.packages.update', $package), [
            'name' => 'Sunrise Canyon Tour',
            'location' => 'Badian, Cebu',
            'description' => 'An updated early guided canyon experience.',
            'price' => 3000,
            'duration' => '5 hours',
            'difficulty' => 'Intermediate',
            'capacity' => 10,
            'available_dates' => [now()->addDays(12)->toDateString()],
            'is_available' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('packages', ['id' => $package->id, 'price' => 3000, 'capacity' => 10]);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $package->image_url));
    }

    public function test_tourist_cannot_book_a_date_that_is_fully_occupied(): void
    {
        $tourist = User::where('role', 'tourist')->firstOrFail();
        $package = Package::firstOrFail();
        $date = now()->addDays(20)->toDateString();
        $package->update(['capacity' => 2, 'available_dates' => [$date]]);
        Booking::create([
            'reference_no' => 'JP-FULL-DATE',
            'package_id' => $package->id,
            'user_id' => $tourist->id,
            'tour_date' => $date,
            'guest_count' => 2,
            'total_amount' => $package->price * 2,
            'status' => 'approved',
        ]);

        $this->actingAs($tourist)->post(route('bookings.store'), [
            'package_id' => $package->id,
            'tour_date' => $date,
            'guest_count' => 1,
            'contact_number' => '09123456789',
        ])->assertSessionHasErrors('tour_date');

        $this->assertSame(2, Booking::where('package_id', $package->id)->whereDate('tour_date', $date)->sum('guest_count'));
    }

    public function test_tourist_cannot_access_package_management(): void
    {
        $tourist = User::where('role', 'tourist')->firstOrFail();
        $this->actingAs($tourist)->get(route('operator.packages.index'))->assertForbidden();
    }
}
