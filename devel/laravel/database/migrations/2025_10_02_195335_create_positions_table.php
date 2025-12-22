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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('security_number')->unique();
            $table->string('position_type');    // LONG | SHORT
            $table->integer('position_quantity');
            $table->string('unit_type');
            $table->string('total_cost_amount');
            $table->string('total_cost_currency');
            $table->string('total_proceeds_amount');
            $table->string('total_proceeds_currency');
            // $table->enum('status', PositionStatusEnum::enumValues());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
