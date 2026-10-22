<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Mueblería La Bendición
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (all authenticated users)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clientes (cobrador scoped)
    Route::resource('clientes', ClienteController::class);

    // Ventas (cobrador scoped)
    Route::resource('ventas', VentaController::class)->except(['edit', 'update']);

    // Pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/pendientes', [PagoController::class, 'pendientes'])->name('pagos.pendientes');
    Route::get('/pagos/{pago}', [PagoController::class, 'show'])->name('pagos.show');
    Route::get('/ventas/{venta}/pagos/nuevo', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/ventas/{venta}/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::delete('/pagos/{pago}', [PagoController::class, 'destroy'])->name('pagos.destroy');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        // Productos
        Route::resource('productos', ProductoController::class);

        // Proveedores
        Route::resource('proveedores', ProveedorController::class);

        // Compras
        Route::resource('compras', CompraController::class)->except(['edit', 'update', 'destroy']);
        Route::post('/compras/{compra}/pagos', [CompraController::class, 'pagoStore'])->name('compras.pagos.store');

        // Usuarios / Cobradores
        Route::resource('usuarios', UsuarioController::class);
    });
});
