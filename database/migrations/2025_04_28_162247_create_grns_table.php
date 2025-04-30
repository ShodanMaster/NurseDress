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
        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->string('grn_no');
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            // $table->string('batch')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status')->default(0)->comment('0=>storage scan not completed;1=>storage scan completed;');
            $table->integer('qc_status')->default(0)->comment('0 => qc not done; 1 => qc done; 2 => qc pending;');
            $table->integer('quantity')->default(0);
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grns');
    }
};
