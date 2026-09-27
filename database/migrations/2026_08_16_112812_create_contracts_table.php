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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('contract_no')->unique();
            $table->enum('contract_type', ['Lump sum', 'Unit price', 'Cost plus', 'Time and material']);
            $table->decimal('original_value', 10, 2);
            $table->decimal('current_value', 10, 2);
            $table->decimal('retention_percentage', 10, 2);
            $table->string('payment_terms');
            $table->date('effective_date');
            $table->enum('status', ['Draft', 'Active', 'Completed', 'Terminated'])->default('Draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
