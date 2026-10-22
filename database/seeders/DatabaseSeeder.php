<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole    = Role::create(['nombre' => 'admin',    'descripcion' => 'Administrador del sistema']);
        $cobradorRole = Role::create(['nombre' => 'cobrador', 'descripcion' => 'Cobrador de clientes']);

        // Admin user
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@bendicion.com',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
            'telefono' => '7000-0001',
            'activo'   => true,
        ]);

        // Cobradores
        $cobrador1 = User::create([
            'name'     => 'Juan Pérez',
            'email'    => 'juan@bendicion.com',
            'password' => Hash::make('password'),
            'role_id'  => $cobradorRole->id,
            'telefono' => '7100-0001',
            'activo'   => true,
        ]);

        $cobrador2 = User::create([
            'name'     => 'María López',
            'email'    => 'maria@bendicion.com',
            'password' => Hash::make('password'),
            'role_id'  => $cobradorRole->id,
            'telefono' => '7100-0002',
            'activo'   => true,
        ]);

        // Categorías
        $categorias = [
            'Salas',
            'Comedores',
            'Dormitorios',
            'Electrodomésticos',
            'Colchones',
            'Mesas y sillas',
        ];

        foreach ($categorias as $cat) {
            Categoria::create(['nombre' => $cat]);
        }

        // Proveedores
        Proveedor::create([
            'nombre'   => 'Muebles del Norte S.A.',
            'contacto' => 'Carlos Ramírez',
            'telefono' => '2222-1111',
            'email'    => 'ventas@mueblesNorte.com',
        ]);

        Proveedor::create([
            'nombre'   => 'Distribuidora El Sol',
            'contacto' => 'Ana González',
            'telefono' => '2333-4444',
        ]);

        // Productos de muestra
        $sala = Categoria::where('nombre', 'Salas')->first();
        Producto::create([
            'codigo'       => 'SAL-001',
            'nombre'       => 'Sala 3 Piezas Moderna',
            'descripcion'  => 'Sala de 3 piezas tapizada en tela, color gris',
            'categoria_id' => $sala->id,
            'precio_costo' => 350.00,
            'precio_venta' => 599.99,
            'stock'        => 10,
            'stock_minimo' => 2,
        ]);

        $comedor = Categoria::where('nombre', 'Comedores')->first();
        Producto::create([
            'codigo'       => 'COM-001',
            'nombre'       => 'Comedor 6 Personas Madera',
            'descripcion'  => 'Mesa de madera sólida con 6 sillas',
            'categoria_id' => $comedor->id,
            'precio_costo' => 280.00,
            'precio_venta' => 450.00,
            'stock'        => 8,
            'stock_minimo' => 2,
        ]);

        $dorm = Categoria::where('nombre', 'Dormitorios')->first();
        Producto::create([
            'codigo'       => 'DOR-001',
            'nombre'       => 'Juego de Dormitorio Completo',
            'descripcion'  => 'Cama matrimonial + cómoda + mesa de noche',
            'categoria_id' => $dorm->id,
            'precio_costo' => 420.00,
            'precio_venta' => 699.99,
            'stock'        => 5,
            'stock_minimo' => 2,
        ]);
    }
}
