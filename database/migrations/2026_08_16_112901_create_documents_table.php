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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            // Public-facing identifier used in share links / URLs so we
            // never leak sequential primary keys externally.
            $table->uuid('uuid')->unique();
            $table->string('title')->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            // Intentionally NOT a foreign key constraint: document_versions
            // references documents, so adding the reverse FK here would
            // create a circular dependency during creation. Integrity is
            // enforced in DocumentVersionService instead.
            $table->unsignedBigInteger('current_version_id')->nullable();

            $table->string('status')->default('active'); // active, archived
            $table->timestamps();
            $table->softDeletes();
            $table->index(['owner_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
