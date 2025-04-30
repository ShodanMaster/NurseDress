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
        Schema::create('qcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grn_id')->constrained('grns')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->double('quantity', 10, 2)->default(0);
            $table->double('scanned_quantity', 10, 2)->default(0);
            $table->double('rejected_quantity', 10, 2)->default(0);
            $table->double('pending_quantity', 10, 2)->default(0);
            $table->double('status')->default(0)->comment('0 = pending, 1 = approved, 2 = rejected');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qcs');
    }
};
