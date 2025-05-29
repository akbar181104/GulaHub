<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('petani_rencana_giling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rencana_giling_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('Menunggu Persetujuan');
            $table->date('tanggal_diajukan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petani_rencana_giling');
    }
};
