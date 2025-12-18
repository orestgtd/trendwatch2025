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
        Schema::create('realized_gain_basis', function (Blueprint $table) {
            $table->id();
            $table->string('security_number');
            $table->string('trade_number');
            $table->integer('base_quantity');
            $table->integer('trade_quantity');
            $table->string('unit_type');
            $table->string('cost_amount');
            $table->string('cost_currency');
            $table->string('proceeds_amount');
            $table->string('proceeds_currency');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realized_gain_basis');
    }
};
