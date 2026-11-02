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
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'category')) {
                $table->string('category')->default('haircut_styling')->after('name');
            }
            if (! Schema::hasColumn('services', 'badge')) {
                $table->string('badge')->nullable()->after('category');
            }
            if (! Schema::hasColumn('services', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            if (! Schema::hasColumn('services', 'show_in_widget')) {
                $table->boolean('show_in_widget')->default(true)->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'badge', 'is_featured', 'show_in_widget']);
        });
    }
};
