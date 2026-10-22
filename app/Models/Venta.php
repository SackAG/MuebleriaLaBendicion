<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_venta', 'cliente_id', 'cobrador_id', 'fecha_venta',
        'subtotal', 'descuento', 'total', 'enganche', 'saldo_pendiente',
        'tipo_pago', 'num_pagos', 'monto_cuota', 'frecuencia_pago',
        'fecha_primer_pago', 'estado', 'notas',
    ];

    protected $casts = [
        'fecha_venta'       => 'date',
        'fecha_primer_pago' => 'date',
        'subtotal'          => 'decimal:2',
        'descuento'         => 'decimal:2',
        'total'             => 'decimal:2',
        'enganche'          => 'decimal:2',
        'saldo_pendiente'   => 'decimal:2',
        'monto_cuota'       => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cobrador()
    {
        return $this->belongsTo(User::class, 'cobrador_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function getTotalPagadoAttribute()
    {
        return $this->pagos()->sum('total_cobrado');
    }
}
