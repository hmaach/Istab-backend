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
        Schema::create('liens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_poste');
            $table->string('libbele');
            $table->string('url');
            $table->timestamps();
            $table->foreign('id_poste')
                ->references('id')
                ->on('postes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liens');
    }
};
