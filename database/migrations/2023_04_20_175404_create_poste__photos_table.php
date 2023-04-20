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
        Schema::create('poste__photos', function (Blueprint $table) {
            $table->primary(['id_poste','id_photo']);
            $table->unsignedBigInteger('id_photo');
            $table->unsignedBigInteger('id_poste');
            $table->timestamps();
            $table->foreign('id_poste')
                ->references('id')
                ->on('postes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('id_photo')
                ->references('id')
                ->on('photos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poste__photos');
    }
};
