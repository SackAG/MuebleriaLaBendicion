<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->cobradorDashboard($user);
    }

    private function adminDashboard()
    {
        $stats = [
            'total_clientes'         => Cliente::count(),
            'total_productos'        => Producto::count(),
            'productos_bajo_stock'   => Producto::whereColumn('stock', '<=', 'stock_minimo')->count(),
            'ventas_activas'         => Venta::where('estado', 'activa')->count(),
            'ventas_mes'             => Venta::whereMonth('fecha_venta', now()->month)
                                            ->whereYear('fecha_venta', now()->year)
                                            ->sum('total'),
            'cobros_mes'             => Pago::whereMonth('fecha_pago', now()->month)
                                            ->whereYear('fecha_pago', now()->year)
                                            ->sum('total_cobrado'),
            'saldo_pendiente_total'  => Venta::where('estado', 'activa')->sum('saldo_pendiente'),
            'compras_pendientes'     => Compra::whereIn('estado', ['pendiente', 'parcial'])->sum('saldo_pendiente'),
            'cobradores'             => User::whereHas('role', fn($q) => $q->where('nombre', 'cobrador'))
                                            ->withCount('clientes')
                                            ->withSum(['pagos' => fn($q) => $q->whereMonth('fecha_pago', now()->month)], 'total_cobrado')
                                            ->get(),
        ];

        $ventasRecientes  = Venta::with(['cliente', 'cobrador'])->latest()->take(10)->get();
        $cobrosRecientes  = Pago::with(['venta.cliente', 'cobrador'])->latest()->take(10)->get();
        $productosBajoStock = Producto::with('categoria')
                                ->whereColumn('stock', '<=', 'stock_minimo')
                                ->orderBy('stock')
                                ->take(10)
                                ->get();

        return view('dashboard.admin', compact('stats', 'ventasRecientes', 'cobrosRecientes', 'productosBajoStock'));
    }

    private function cobradorDashboard(User $user)
    {
        $stats = [
            'mis_clientes'          => Cliente::where('cobrador_id', $user->id)->count(),
            'ventas_activas'        => Venta::where('cobrador_id', $user->id)->where('estado', 'activa')->count(),
            'cobros_hoy'            => Pago::where('cobrador_id', $user->id)->whereDate('fecha_pago', today())->sum('total_cobrado'),
            'cobros_mes'            => Pago::where('cobrador_id', $user->id)
                                          ->whereMonth('fecha_pago', now()->month)
                                          ->whereYear('fecha_pago', now()->year)
                                          ->sum('total_cobrado'),
            'saldo_pendiente'       => Venta::where('cobrador_id', $user->id)->where('estado', 'activa')->sum('saldo_pendiente'),
        ];

        $ventasPendientes = Venta::where('cobrador_id', $user->id)
                                ->where('estado', 'activa')
                                ->with('cliente')
                                ->orderBy('saldo_pendiente', 'desc')
                                ->take(10)
                                ->get();

        $cobrosRecientes = Pago::where('cobrador_id', $user->id)
                              ->with('venta.cliente')
                              ->latest()
                              ->take(10)
                              ->get();

        return view('dashboard.cobrador', compact('stats', 'ventasPendientes', 'cobrosRecientes'));
    }
}
