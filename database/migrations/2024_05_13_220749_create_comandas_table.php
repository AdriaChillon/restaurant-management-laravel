<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComandasTable extends Migration
{
    public function up()
    {
        Schema::create('comandas', function (Blueprint $table) {
            $table->bigIncrements('id');  // Cambiamos a bigIncrements
            $table->unsignedBigInteger('mesa_id');
            $table->dateTime('fecha_hora');
            $table->boolean('en_marcha');
            $table->decimal('precio_total', 8, 2)->default(0.00);
            $table->boolean('pagado')->default(false);
            $table->timestamps();

            $table->foreign('mesa_id')->references('id')->on('mesas')->onDelete('cascade');
        });

        Schema::create('comanda_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('comanda_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->boolean('preparado')->default(false); // Nuevo campo para marcar si el producto está preparado
        
            $table->primary(['comanda_id', 'producto_id']);
            $table->foreign('comanda_id')->references('id')->on('comandas')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('comanda_productos');
        Schema::dropIfExists('comandas');
    }
}
