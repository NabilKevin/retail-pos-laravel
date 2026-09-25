<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Note: enum() compiles to VARCHAR with a CHECK constraint on SQLite,
     * which works correctly for string comparisons.
     */
    public function up(): void
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->integer('total_transaksi');
            $table->integer('total_dibayar');
            $table->integer('total_kembalian');
            $table->integer('total_return')->default(0);
            $table->unsignedBigInteger('user_id');
            // Using string instead of enum for full SQLite/MySQL compatibility.
            // Valid values: 'SUCCESS', 'VOID', 'RETURN'
            $table->string('status', 20)->default('SUCCESS');
            $table->string('void_reason')->nullable();
            $table->unsignedBigInteger('void_by')->nullable();
            $table->timestamp('void_at')->nullable();
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
