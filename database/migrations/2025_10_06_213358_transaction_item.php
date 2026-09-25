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
        Schema::create('transactionitem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obat_id');
            $table->unsignedBigInteger('transaction_id');
            $table->integer('harga_modal');
            $table->integer('harga_jual');
            $table->integer('qty');
            $table->bigInteger('subtotal');
            $table->timestamps();

            $table->foreign('obat_id')->references('id')->on('obat')->onDelete('restrict');
            $table->foreign('transaction_id')->references('id')->on('transaction')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactionitem');
    }
};
