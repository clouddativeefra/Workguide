<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('cantidad_trabajadores');
            $table->string('descripcion');
            $table->string('ayuda');
            $table->unsignedBigInteger('trabajadores_id');
            $table->foreign('trabajadores_id')->references('id')->on('trabajadores')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['trabajadores_id']);
        });

        Schema::dropIfExists('trabajadores');
    }
};
