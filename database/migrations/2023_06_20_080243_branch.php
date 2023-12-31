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
         Schema::create('branch', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouse')->onDelete('cascade');
            $table->foreignId('account_id');
            $table->foreign('account_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('profile_logo');
            $table->string('address');
            $table->time('time')->nullable()->default(NULL);;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('branch');
    }
};
