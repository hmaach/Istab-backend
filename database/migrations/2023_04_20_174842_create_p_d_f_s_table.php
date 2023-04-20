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
        Schema::create('p_d_f_s', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->unsignedBigInteger('id_classe');
            $table->unsignedBigInteger('id_poste');
            $table->foreign('id_poste')
                ->references('id')
                ->on('postes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('id_classe')
                ->references('id')
                ->on('classe__p_d_f_s')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_d_f_s');
    }
};
