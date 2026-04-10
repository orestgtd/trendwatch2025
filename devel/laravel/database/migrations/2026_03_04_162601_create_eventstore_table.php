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
        Schema::create('eventstore', function (Blueprint $table) {
            $table->id();

            $table->string('aggregate_id');
            $table->string('aggregate_type');
            $table->string('event_type');

            $table->json('payload');

            $table->unsignedBigInteger('version');
            $table->timestamp('occurred_at');

            $table->timestamps();

            $table->index(['aggregate_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
