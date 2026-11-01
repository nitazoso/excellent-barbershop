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
        Schema::table('barbers', function (Blueprint $table) {
            if (! Schema::hasColumn('barbers', 'chair_number')) {
                $table->unsignedTinyInteger('chair_number')->default(1)->after('name');
            }
            if (! Schema::hasColumn('barbers', 'role_title')) {
                $table->string('role_title')->nullable()->after('chair_number');
            }
            if (! Schema::hasColumn('barbers', 'specialization')) {
                $table->string('specialization')->nullable()->after('role_title');
            }
            if (! Schema::hasColumn('barbers', 'skills_tags')) {
                $table->text('skills_tags')->nullable()->after('specialization');
            }
            if (! Schema::hasColumn('barbers', 'availability_status')) {
                $table->string('availability_status')->default('online')->after('skills_tags');
            }
            if (! Schema::hasColumn('barbers', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('availability_status');
            }
            if (! Schema::hasColumn('barbers', 'review_count')) {
                $table->unsignedInteger('review_count')->default(0)->after('rating');
            }
            if (! Schema::hasColumn('barbers', 'scheduled_sessions_count')) {
                $table->unsignedInteger('scheduled_sessions_count')->default(0)->after('review_count');
            }
            if (! Schema::hasColumn('barbers', 'accept_public_booking')) {
                $table->boolean('accept_public_booking')->default(true)->after('scheduled_sessions_count');
            }
            if (! Schema::hasColumn('barbers', 'warning_note')) {
                $table->text('warning_note')->nullable()->after('accept_public_booking');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbers', function (Blueprint $table) {
            $table->dropColumn([
                'chair_number',
                'role_title',
                'specialization',
                'skills_tags',
                'availability_status',
                'rating',
                'review_count',
                'scheduled_sessions_count',
                'accept_public_booking',
                'warning_note',
            ]);
        });
    }
};
