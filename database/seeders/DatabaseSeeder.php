<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tourist = User::updateOrCreate(['email' => 'tourist@joepro.test'], ['name' => 'Maria Santos', 'password' => Hash::make('Tourist123!'), 'role' => 'tourist']);
        User::updateOrCreate(['email' => 'operator@joepro.test'], ['name' => 'JoePro Operator', 'password' => Hash::make('Operator123!'), 'role' => 'operator']);
        $standard = Package::updateOrCreate(['name' => 'Standard Package'], ['location' => 'Badian, Cebu', 'description' => 'Eco entrance, canyon transport, complete safety gear, snacks, water, and lunch.', 'price' => 2100, 'duration' => '4–5 hours', 'difficulty' => 'Best value', 'capacity' => 12, 'image_url' => 'https://thesunrisedreamers.com/wp-content/uploads/2022/11/Best-things-to-do-in-moalboal-kawasan-falls.webp', 'is_available' => true]);
        $private = Package::updateOrCreate(['name' => 'Private Package'], ['location' => 'Cebu City to Badian', 'description' => 'Private transportation, flexible pickup, all safety gear, lunch, and a dedicated local guide.', 'price' => 6500, 'duration' => 'Full day', 'difficulty' => 'Private tour', 'capacity' => 10, 'image_url' => 'https://gretastravels.com/wp-content/uploads/2019/06/GOPR1846.jpg', 'is_available' => true]);
        Booking::firstOrCreate(['reference_no' => 'JP-2026-A7K2Q'], ['package_id' => $standard->id, 'user_id' => $tourist->id, 'tour_date' => now()->addDays(2), 'guest_count' => 2, 'total_amount' => 4200, 'status' => 'approved']);
        Booking::firstOrCreate(['reference_no' => 'JP-2026-B9M4R'], ['package_id' => $private->id, 'user_id' => $tourist->id, 'tour_date' => now()->addDays(5), 'guest_count' => 4, 'total_amount' => 26000, 'status' => 'pending']);
    }
}
