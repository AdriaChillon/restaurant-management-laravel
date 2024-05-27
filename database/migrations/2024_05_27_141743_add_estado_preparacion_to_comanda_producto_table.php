<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoPreparacionToComandaProductoTable extends Migration
{
    public function up()
    {
        Schema::table('comanda_productos', function (Blueprint $table) {
            $table->enum('estado_preparacion', ['pendiente', 'en_proceso', 'listo'])->default('pendiente');
        });
    }

    public function down()
    {
        Schema::table('comanda_productos', function (Blueprint $table) {
            $table->dropColumn('estado_preparacion');
        });
    }
}
