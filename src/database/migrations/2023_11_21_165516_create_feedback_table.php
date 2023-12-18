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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->unsignedBigInteger('practice_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->foreign('practice_id')->references('id')->on('practices')->onDelete("cascade");
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
