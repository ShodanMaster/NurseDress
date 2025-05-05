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
        Schema::create('rejection_scans', function (Blueprint $table) {
            $table->id();
            $table->string('barcode');
            $table->foreignId('grn_id')->constrained('grns');
            $table->foreignId('bin_id')->constrained('bins');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('scanned_quantity')->default(1);
            $table->timestamp('scanned_time')->useCurrent();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejection_scans');
    }
};
