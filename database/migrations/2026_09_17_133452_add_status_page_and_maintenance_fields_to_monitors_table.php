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
        Schema::table('monitors', function (Blueprint $table) {
            $table->boolean('show_on_status_page')->default(true)->after('is_paused');
            $table->boolean('is_maintenance')->default(false)->after('show_on_status_page');
            $table->timestamp('maintenance_starts_at')->nullable()->after('is_maintenance');
            $table->timestamp('maintenance_ends_at')->nullable()->after('maintenance_starts_at');
            $table->string('group')->nullable()->after('maintenance_ends_at');
            $table->integer('sort_order')->default(0)->after('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropColumn([
                'show_on_status_page',
                'is_maintenance',
                'maintenance_starts_at',
                'maintenance_ends_at',
                'group',
                'sort_order'
            ]);
        });
    }
};
