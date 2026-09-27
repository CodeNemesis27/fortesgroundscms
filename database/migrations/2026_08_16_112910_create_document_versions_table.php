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
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_number');

            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('size_bytes');

            // Path to the CIPHERTEXT on the private disk. Never a public path.
            $table->string('storage_path');

            // SHA-256 of the PLAINTEXT, used to verify integrity after decryption.
            $table->string('sha256_checksum', 64);

            // Envelope encryption: a random per-version Data Encryption Key
            // (DEK) is generated, used once to encrypt the file, and then
            // itself encrypted ("wrapped") with the application's APP_KEY
            // before being stored here. See DocumentEncryptionService.
            $table->text('encrypted_data_key');
            $table->string('encryption_iv', 32);   // hex-encoded 12-byte GCM nonce
            $table->string('encryption_tag', 32);  // hex-encoded 16-byte GCM auth tag
            $table->string('encryption_cipher')->default('aes-256-gcm');

            $table->foreignId('uploaded_by')->constrained('users');
            $table->text('change_notes')->nullable();

            $table->timestamps();

            $table->unique(['document_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
