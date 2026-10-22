<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'categoria_id',
        'precio_costo', 'precio_venta', 'stock', 'stock_minimo',
        'imagen', 'activo',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'precio_costo'  => 'decimal:2',
        'precio_venta'  => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function compraDetalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function getBajoStockAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}
