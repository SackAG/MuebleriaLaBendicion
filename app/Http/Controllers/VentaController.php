<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    private function query()
    {
        $user = auth()->user();
        $q = Venta::with(['cliente', 'cobrador']);
        if ($user->isCobrador()) {
            $q->where('cobrador_id', $user->id);
        }
        return $q;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $estado = $request->get('estado');

        $ventas = $this->query()
            ->when($search, fn($q) => $q->whereHas('cliente', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%");
            })->orWhere('numero_venta', 'like', "%{$search}%"))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->latest()
            ->paginate(20);

        return view('ventas.index', compact('ventas', 'search', 'estado'));
    }

    public function create()
    {
        $user     = auth()->user();
        $clientes = $user->isAdmin()
            ? Cliente::where('activo', true)->orderBy('nombre')->get()
            : Cliente::where('cobrador_id', $user->id)->where('activo', true)->orderBy('nombre')->get();

        $productos = Producto::where('activo', true)->where('stock', '>', 0)->orderBy('nombre')->get();

        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'        => 'required|exists:clientes,id',
            'fecha_venta'       => 'required|date',
            'tipo_pago'         => 'required|in:contado,credito',
            'enganche'          => 'nullable|numeric|min:0',
            'num_pagos'         => 'nullable|integer|min:1',
            'frecuencia_pago'   => 'nullable|in:semanal,quincenal,mensual',
            'fecha_primer_pago' => 'nullable|date',
            'notas'             => 'nullable|string',
            'productos'         => 'required|array|min:1',
            'productos.*.id'    => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio'   => 'required|numeric|min:0',
            'productos.*.descuento'=> 'nullable|numeric|min:0',
        ]);

        $cliente = Cliente::findOrFail($data['cliente_id']);
        if (auth()->user()->isCobrador() && $cliente->cobrador_id !== auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($data, $cliente) {
            $subtotal  = 0;
            $descuento = 0;

            foreach ($data['productos'] as $item) {
                $prod = Producto::findOrFail($item['id']);
                if ($prod->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$prod->nombre}");
                }
                $itemSubtotal = $item['precio'] * $item['cantidad'];
                $itemDescuento = ($item['descuento'] ?? 0) * $item['cantidad'];
                $subtotal  += $itemSubtotal;
                $descuento += $itemDescuento;
            }

            $total           = $subtotal - $descuento;
            $enganche        = $data['enganche'] ?? 0;
            $saldoPendiente  = $total - $enganche;
            $numPagos        = $data['num_pagos'] ?? 1;
            $montoCuota      = $numPagos > 0 ? round($saldoPendiente / $numPagos, 2) : 0;

            $cobrador_id = auth()->user()->isAdmin() ? $cliente->cobrador_id : auth()->id();

            $venta = Venta::create([
                'numero_venta'      => 'V-' . str_pad(Venta::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'cliente_id'        => $cliente->id,
                'cobrador_id'       => $cobrador_id,
                'fecha_venta'       => $data['fecha_venta'],
                'subtotal'          => $subtotal,
                'descuento'         => $descuento,
                'total'             => $total,
                'enganche'          => $enganche,
                'saldo_pendiente'   => $saldoPendiente,
                'tipo_pago'         => $data['tipo_pago'],
                'num_pagos'         => $numPagos,
                'monto_cuota'       => $montoCuota,
                'frecuencia_pago'   => $data['frecuencia_pago'] ?? 'mensual',
                'fecha_primer_pago' => $data['fecha_primer_pago'] ?? null,
                'estado'            => $saldoPendiente <= 0 ? 'liquidada' : 'activa',
                'notas'             => $data['notas'] ?? null,
            ]);

            foreach ($data['productos'] as $item) {
                $prod = Producto::findOrFail($item['id']);
                VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $prod->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento'       => $item['descuento'] ?? 0,
                    'subtotal'        => ($item['precio'] - ($item['descuento'] ?? 0)) * $item['cantidad'],
                ]);
                $prod->decrement('stock', $item['cantidad']);
            }
        });

        return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente.');
    }

    public function show(Venta $venta)
    {
        $this->authorizeVenta($venta);
        $venta->load(['cliente', 'cobrador', 'detalles.producto', 'pagos.cobrador']);
        return view('ventas.show', compact('venta'));
    }

    public function destroy(Venta $venta)
    {
        $this->authorizeVenta($venta);
        $venta->update(['estado' => 'cancelada']);
        return redirect()->route('ventas.index')->with('success', 'Venta cancelada.');
    }

    private function authorizeVenta(Venta $venta): void
    {
        if (auth()->user()->isCobrador() && $venta->cobrador_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a esta venta.');
        }
    }
}
