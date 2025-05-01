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
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->foreignId('grn_id')->constrained('grns');
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('quantity')->default(1);
            $table->enum('status', ['-1', '0', '1', '2', '3', '4', '5', '6', '8', '9'])
                        ->default('-1')
                        ->comment('-1=>NIS,0=>purchase return,1=>stock,2=>Dispatch,3=>Rejection,4=>Transit,8=>stock_out,5=>production,9=>repacked');
            $table->integer('qc_status')->default(0)->comment('0:Not checked, 1: Passed, 2: Failed');
            $table->double('price',10,2)->default(0);
            $table->double('total_price',10,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcodes');
    }
};
