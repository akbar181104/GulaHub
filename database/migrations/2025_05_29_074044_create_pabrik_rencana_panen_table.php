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
        Schema::create('pabrik_rencana_panen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pabrik_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rencana_panen_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('pabrik_rencana_panen');
    }
};
