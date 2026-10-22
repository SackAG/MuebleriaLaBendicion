<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_venta')->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');
            $table->foreignId('cobrador_id')->constrained('users')->onDelete('restrict');
            $table->date('fecha_venta');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('enganche', 10, 2)->default(0); // down payment
            $table->decimal('saldo_pendiente', 10, 2)->default(0);
            $table->enum('tipo_pago', ['contado', 'credito'])->default('credito');
            $table->integer('num_pagos')->default(1); // number of installments
            $table->decimal('monto_cuota', 10, 2)->default(0);
            $table->enum('frecuencia_pago', ['semanal', 'quincenal', 'mensual'])->default('mensual');
            $table->date('fecha_primer_pago')->nullable();
            $table->enum('estado', ['activa', 'liquidada', 'cancelada'])->default('activa');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
        Schema::dropIfExists('ventas');
    }
};
