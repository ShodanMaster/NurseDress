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
        Schema::create('grn_subs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grn_id')->constrained('grns')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('barcodes');
            $table->integer('accepted_qty')->default(0);
            $table->integer('rejected_qty')->default(0);
            $table->integer('pending_qty')->default(null);
            $table->integer('grn_status')->default(0);
            $table->integer('status')->default(0);
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_subs');
    }
};
