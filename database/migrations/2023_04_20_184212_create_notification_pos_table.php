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
        Schema::create('notification_pos', function (Blueprint $table) {
            $table->id();
            $table->string("libelle")->nullable();
            $table->unsignedBigInteger('id_poste');
            $table->foreign('id_poste')
                ->references('id')
                ->on('postes')
                ->onUpdate('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_pos');
    }
};
