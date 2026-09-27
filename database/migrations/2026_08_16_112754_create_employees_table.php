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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->enum('employee_type', ['Regular', 'Contractual', 'Project based']);
            $table->date('date_hired');
            $table->date('date_terminated')->nullable();
            $table->decimal('basic_rate', 10, 2);
            $table->enum('rate_type', ['Hourly', 'Daily', 'Monthly']);
            $table->string('contact_no');
            $table->text('address');
            $table->enum('status', ['Active', 'On-leave', 'Terminated']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
