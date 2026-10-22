<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\PagoCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $estado = $request->get('estado');

        $compras = Compra::with(['proveedor', 'creadoPor'])
            ->when($search, fn($q) => $q->whereHas('proveedor', fn($q) => $q->where('nombre', 'like', "%{$search}%"))
                ->orWhere('numero_compra', 'like', "%{$search}%"))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->latest()
            ->paginate(20);

        return view('compras.index', compact('compras', 'search', 'estado'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $productos   = Producto::where('activo', true)->orderBy('nombre')->get();
        return view('compras.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proveedor_id'         => 'required|exists:proveedores,id',
            'fecha_compra'         => 'required|date',
            'notas'                => 'nullable|string',
            'productos'            => 'required|array|min:1',
            'productos.*.id'       => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = 0;
            foreach ($data['productos'] as $item) {
                $subtotal += $item['precio'] * $item['cantidad'];
            }

            $compra = Compra::create([
                'numero_compra'  => 'C-' . str_pad(Compra::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'proveedor_id'   => $data['proveedor_id'],
                'fecha_compra'   => $data['fecha_compra'],
                'subtotal'       => $subtotal,
                'descuento'      => 0,
                'total'          => $subtotal,
                'monto_pagado'   => 0,
                'saldo_pendiente'=> $subtotal,
                'estado'         => 'pendiente',
                'notas'          => $data['notas'] ?? null,
                'creado_por'     => auth()->id(),
            ]);

            foreach ($data['productos'] as $item) {
                CompraDetalle::create([
                    'compra_id'       => $compra->id,
                    'producto_id'     => $item['id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $item['precio'] * $item['cantidad'],
                ]);
                Producto::find($item['id'])->increment('stock', $item['cantidad']);
            }
        });

        return redirect()->route('compras.index')->with('success', 'Compra registrada exitosamente.');
    }

    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'creadoPor', 'detalles.producto', 'pagos.registradoPor']);
        return view('compras.show', compact('compra'));
    }

    public function pagoStore(Request $request, Compra $compra)
    {
        $data = $request->validate([
            'fecha_pago'  => 'required|date',
            'monto'       => 'required|numeric|min:0.01|max:' . $compra->saldo_pendiente,
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,otro',
            'notas'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $compra) {
            PagoCompra::create([
                'compra_id'      => $compra->id,
                'fecha_pago'     => $data['fecha_pago'],
                'monto'          => $data['monto'],
                'metodo_pago'    => $data['metodo_pago'],
                'notas'          => $data['notas'] ?? null,
                'registrado_por' => auth()->id(),
            ]);

            $nuevoMontoPagado   = $compra->monto_pagado + $data['monto'];
            $nuevoSaldo         = $compra->total - $nuevoMontoPagado;

            $estado = match(true) {
                $nuevoSaldo <= 0                         => 'pagada',
                $nuevoMontoPagado > 0 && $nuevoSaldo > 0 => 'parcial',
                default                                  => 'pendiente',
            };

            $compra->update([
                'monto_pagado'    => $nuevoMontoPagado,
                'saldo_pendiente' => max(0, $nuevoSaldo),
                'estado'          => $estado,
            ]);
        });

        return redirect()->route('compras.show', $compra)->with('success', 'Pago a proveedor registrado.');
    }
}
