<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('securities', function (Blueprint $table) {
            $table->id();
            $table->string('security_number');
            $table->string('symbol');
            $table->string('canonical_description');
            $table->json('variations')->nullable();
            $table->enum('unit_type', ['SHARES', 'CONTRACTS']);
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('securities');
    }
};
