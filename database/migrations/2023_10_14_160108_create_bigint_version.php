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
        Schema::create('bigint_version', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamp('order_date')->index();
        });

        Schema::create('bigint', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamp('order_date', 6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bigint_version');
    }
};
