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
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barcode');
            $table->string('nama');
            $table->integer('stok')->default(0);
            $table->unsignedBigInteger('tipe_id');
            $table->integer('harga_modal');
            $table->integer('harga_jual');
            $table->timestamp('expired_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('tipe_id')->references('id')->on('tipeobat')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
