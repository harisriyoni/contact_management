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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string("first_name", 100)->nullable(false);
            $table->string("last_name", 100)->nullable();
            $table->string("email", 200)->nullable();
            $table->string("phone", 20)->nullable();
            $table->unsignedBigInteger("users_id")->nullable(false);
            $table->timestamps();
            $table->foreign("users_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};