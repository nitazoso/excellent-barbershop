<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 30)->unique();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->foreignId('barber_id')
                ->constrained('barbers')
                ->restrictOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->restrictOnDelete();

            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('status', 20)->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['booking_date', 'status']);
            $table->index(['customer_id', 'booking_date']);
            $table->index(['barber_id', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
