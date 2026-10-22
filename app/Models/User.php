<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'activo'            => 'boolean',
    ];

    // ----- Relationships -----

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'cobrador_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cobrador_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'cobrador_id');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'creado_por');
    }

    // ----- Helpers -----

    public function isAdmin(): bool
    {
        return $this->role->nombre === 'admin';
    }

    public function isCobrador(): bool
    {
        return $this->role->nombre === 'cobrador';
    }
}
