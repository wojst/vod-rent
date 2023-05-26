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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->boolean('admin_role')->default(false);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('orders_count')->default(0);
            $table->boolean('loyalty_card')->default(false);
            $table->unsignedBigInteger('id_fav_category')->nullable();
            $table->foreign('id_fav_category')->references('category_id')->on('categories');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
