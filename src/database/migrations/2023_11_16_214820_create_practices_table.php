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
        Schema::create('practices', function (Blueprint $table) {
            $table->id();
            $table->date('from');
            $table->date('to');
            $table->foreignId('user_id')->constrained()->onDelete("cascade");
            $table->foreignId('company_employee_id')->constrained();
            $table->foreignId('program_id')->constrained();
            $table->string('contract',255)->nullable();
            $table->string('completion_confirmation',255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practices');
    }
};
