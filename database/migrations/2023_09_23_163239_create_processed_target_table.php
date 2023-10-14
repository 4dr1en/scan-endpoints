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
        Schema::create('processed_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('targets_monitored_id')->constrained()->onDelete('cascade');
            $table->integer('response_code');
            $table->integer('response_time');
            $table->timestampTz('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processed_targets');
    }
};
