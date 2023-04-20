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
        Schema::create('reacts', function (Blueprint $table) {
            $table->primary(['id_poste','id_user']);
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_poste');
            $table->timestamps();
            $table->foreign('id_poste')
                ->references('id')
                ->on('postes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reacts');
    }
};
