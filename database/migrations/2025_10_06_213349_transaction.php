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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->integer('total_transaksi');
            $table->integer('total_dibayar');
            $table->integer('total_kembalian');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['SUCCESS','VOID','REFUND'])->default('SUCCESS');
            $table->timestamp('paid_at')->nullable()->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
