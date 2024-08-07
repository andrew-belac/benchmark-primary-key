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
        Schema::create('uuid_v7_binary', function (Blueprint $table) {
            $table->char('id', 16)->charset('binary')->primary();
            $table->string('name', 100);
            $table->timestamp('order_date', 6)->index();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uuid_v7');
    }
};
