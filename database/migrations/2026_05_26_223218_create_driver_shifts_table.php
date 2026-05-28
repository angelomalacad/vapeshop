<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('driver_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->date('shift_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('22:00:00');
            $table->text('notes')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // One driver can only have one active shift per day
            $table->unique(['driver_id', 'shift_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('driver_shifts');
    }
};