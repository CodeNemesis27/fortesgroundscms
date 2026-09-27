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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->string('name');
            $table->text('description');
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->enum('project_type', ['New build', 'Renovation', 'Infrastructure', 'Maintenance']);
            $table->text('site_address');
            $table->date('start_date');
            $table->date('expected_end_date');
            $table->date('actual_end_date')->nullable();
            $table->enum('status', ['Bidding', 'Planning', 'In progress', 'On hold', 'Substantially complete', 'Cancelled']);
            $table->decimal('contract_value', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
