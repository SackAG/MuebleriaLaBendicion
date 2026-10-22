<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_recibo')->unique();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('restrict');
            $table->foreignId('cobrador_id')->constrained('users')->onDelete('restrict');
            $table->date('fecha_pago');
            $table->decimal('monto', 10, 2);
            $table->decimal('mora', 10, 2)->default(0);
            $table->decimal('total_cobrado', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'otro'])->default('efectivo');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
