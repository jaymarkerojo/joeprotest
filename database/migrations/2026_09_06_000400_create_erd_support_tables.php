<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained();
            $t->enum('payment_method', ['gcash', 'bank_transfer', 'cash']);
            $t->decimal('amount', 10, 2);
            $t->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $t->string('reference_number')->nullable();
            $t->text('screenshot_url')->nullable();
            $t->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('verified_at')->nullable();
            $t->timestamps();
        });
        Schema::create('notifications', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
            $t->text('message');
            $t->enum('type', ['booking', 'payment', 'system']);
            $t->boolean('is_read')->default(false);
            $t->timestamps();
        });
        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('rating');
            $t->text('comment')->nullable();
            $t->timestamps();
        });
        Schema::create('admin_review_replies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('review_id')->constrained()->cascadeOnDelete();
            $t->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $t->text('reply_text');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_review_replies');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('payments');
    }
};
