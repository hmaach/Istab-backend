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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('description');
            $table->string('titre');
            $table->string('couleur')->nullable();
            $table->string('type')->nullable();
            $table->string('audience')->default('public');
            $table->integer('audience_id')->nullable();
            $table->date('dateDeb');
            $table->date('dateFin');
            $table->time('timeDeb');
            $table->time('timeFin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
