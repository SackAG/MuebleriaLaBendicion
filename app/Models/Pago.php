<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_recibo', 'venta_id', 'cobrador_id', 'fecha_pago',
        'monto', 'mora', 'total_cobrado', 'metodo_pago', 'notas',
    ];

    protected $casts = [
        'fecha_pago'    => 'date',
        'monto'         => 'decimal:2',
        'mora'          => 'decimal:2',
        'total_cobrado' => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function cobrador()
    {
        return $this->belongsTo(User::class, 'cobrador_id');
    }
}
