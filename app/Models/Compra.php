<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_compra', 'proveedor_id', 'fecha_compra',
        'subtotal', 'descuento', 'total', 'monto_pagado',
        'saldo_pendiente', 'estado', 'notas', 'creado_por',
    ];

    protected $casts = [
        'fecha_compra'    => 'date',
        'subtotal'        => 'decimal:2',
        'descuento'       => 'decimal:2',
        'total'           => 'decimal:2',
        'monto_pagado'    => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function pagos()
    {
        return $this->hasMany(PagoCompra::class);
    }
}
