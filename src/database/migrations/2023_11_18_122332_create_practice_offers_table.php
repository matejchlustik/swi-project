<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('practice_offers', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('phone', 15);
            $table->string('email', 45);
            $table->unsignedBigInteger('company_department_id');
            $table->timestamps();
            $table->foreign('company_department_id')->references('id')->on('company_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_offers');
    }
};
