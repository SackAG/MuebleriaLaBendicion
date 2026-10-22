<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'email', 'direccion',
        'dui', 'nit', 'referencias', 'cobrador_id', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function cobrador()
    {
        return $this->belongsTo(User::class, 'cobrador_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getSaldoTotalAttribute()
    {
        return $this->ventas()->where('estado', 'activa')->sum('saldo_pendiente');
    }
}
