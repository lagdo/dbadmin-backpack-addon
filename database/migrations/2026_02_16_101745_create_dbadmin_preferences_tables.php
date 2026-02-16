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
        Schema::create('dbadmin_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('description', 500);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('dbadmin_users');
        });
        Schema::create('dbadmin_preferences', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('category');
            $table->json('content');
            $table->timestamp('last_update');
            $table->unsignedBigInteger('profile_id');
            $table->foreign('profile_id')->references('id')->on('dbadmin_profiles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dbadmin_preferences');
        Schema::dropIfExists('dbadmin_profiles');
    }
};
