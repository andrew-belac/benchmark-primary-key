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
        Schema::create('range_check', function (Blueprint $table) {
            $table->string('id', 26)->primary();
            $table->timestamp('check_date', 6)->index();
            $table->timestamp('created_at', 6)->index();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uuidv4');
    }
};
