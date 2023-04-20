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
        Schema::create('groupe__photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_photo');
            $table->unsignedBigInteger('id_groupe');
            $table->primary(['id_groupe','id_photo']);
            $table->foreign('id_groupe')
                ->references('id')
                ->on('groupes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('id_photo')
                ->references('id')
                ->on('photos')
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
        Schema::dropIfExists('groupe__photos');
    }
};
