<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Check if the audit logs feature is enabled.
     */
    private function auditEnabled(): bool
    {
        return config('dbadmin.audit.enduser.enabled') ||
            config('dbadmin.audit.history.enabled') ||
            config('dbadmin.audit.favorite.enabled');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Do not create the tables if the feature is not enabled.
        if (!$this->auditEnabled()) {
            return;
        }

        $connection = Schema::connection('database.dbadmin');

        $connection->create('dbadmin_owners', function (Blueprint $table) {
            $table->id();
            $table->string('username', 150);
            $table->unique('username');
        });
        $connection->create('dbadmin_runned_commands', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->string('driver', 30);
            $table->json('options');
            $table->smallInteger('category');
            $table->timestamp('last_update');
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('dbadmin_owners');
        });
        $connection->create('dbadmin_stored_commands', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('query');
            $table->string('driver', 30);
            $table->timestamp('last_update');
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('dbadmin_owners');
        });
        $connection->create('dbadmin_tags', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('dbadmin_owners');
            $table->unique('title', 'owner_id');
        });
        $connection->create('dbadmin_command_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('command_id');
            $table->foreign('command_id')->references('id')->on('dbadmin_stored_commands');
            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('dbadmin_tags');
            $table->unique('command_id', 'tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = Schema::connection('database.dbadmin');

        $connection->dropIfExists('dbadmin_command_tag');
        $connection->dropIfExists('dbadmin_tags');
        $connection->dropIfExists('dbadmin_stored_commands');
        $connection->dropIfExists('dbadmin_runned_commands');
        $connection->dropIfExists('dbadmin_owners');
    }
};
