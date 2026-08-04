<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_barangay', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('barangay_name');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->index('barangay_name');
            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_barangay');
    }
};