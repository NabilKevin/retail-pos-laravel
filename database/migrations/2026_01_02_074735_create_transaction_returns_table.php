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
        Schema::create('transaction_returns', function (Blueprint $table) {
        $table->id();

        $table->foreignId('transaction_id')
            ->constrained('transaction')
            ->cascadeOnDelete();

        $table->foreignId('transaction_item_id')
            ->constrained('transactionitem')
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->constrained('user')
            ->restrictOnDelete();

        $table->integer('qty');
        $table->decimal('amount', 15, 2);
        $table->string('reason');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_returns');
    }
};
