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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type', 16)->default('http');
            $table->text('url');
            $table->string('method', 8)->default('GET');
            $table->string('status')->default('PENDING')->index();
            $table->unsignedInteger('interval_seconds')->default(60);
            $table->unsignedSmallInteger('timeout_seconds')->default(10);
            $table->jsonb('headers')->nullable();
            $table->jsonb('expected_status_codes')->nullable();
            $table->unsignedInteger('consecutive_failures')->default(0);
            $table->timestampTz('last_checked_at')->nullable();
            $table->timestampTz('next_check_at')->nullable()->index();
            $table->unsignedInteger('last_latency_ms')->nullable();
            $table->timestamps();
            $table->index(['team_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
