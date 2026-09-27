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
        Schema::create('document_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shared_by')->constrained('users');

            // Null when this is an external link share rather than a
            // share with a specific registered user.
            $table->foreignId('shared_with_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('permission')->default('view'); // view, download, edit

            // High-entropy token for external link shares. Never guessable,
            // never sequential.
            $table->string('token', 64)->nullable()->unique();
            $table->string('password_hash')->nullable(); // optional link password
            $table->unsignedInteger('max_downloads')->nullable();
            $table->unsignedInteger('download_count')->default(0);

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();

            $table->timestamps();

            $table->index(['document_id', 'shared_with_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_shares');
    }
};
