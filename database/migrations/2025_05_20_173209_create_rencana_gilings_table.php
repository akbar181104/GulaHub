<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rencana_gilings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID pabrik yang mengajukan
            $table->foreignId('target_id')->nullable()->constrained('users')->nullOnDelete(); // ID petani yang dituju
            $table->string('kebutuhan_giling');
            $table->date('tanggal');
            $table->string('status')->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_gilings');
    }
};
