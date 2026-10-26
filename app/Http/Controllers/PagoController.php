<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    private function query()
    {
        $user = auth()->user();
        $q = Pago::with(['venta.cliente', 'cobrador']);
        if ($user->isCobrador()) {
            $q->where('cobrador_id', $user->id);
        }
        return $q;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $fecha  = $request->get('fecha');

        $pagos = $this->query()
            ->when($search, fn($q) => $q->whereHas('venta.cliente', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%");
            })->orWhere('numero_recibo', 'like', "%{$search}%"))
            ->when($fecha, fn($q) => $q->whereDate('fecha_pago', $fecha))
            ->latest()
            ->paginate(20);

        return view('pagos.index', compact('pagos', 'search', 'fecha'));
    }

    public function pendientes(Request $request)
    {
        $user = auth()->user();

        $ventas = Venta::with(['cliente', 'cobrador'])
            ->where('estado', 'activa')
            ->where('saldo_pendiente', '>', 0)
            ->when($user->isCobrador(), fn($q) => $q->where('cobrador_id', $user->id))
            ->orderBy('saldo_pendiente', 'desc')
            ->paginate(20);

        return view('pagos.pendientes', compact('ventas'));
    }

    public function create(Venta $venta)
{
    if (auth()->user()->isCobrador() && $venta->cobrador_id !== auth()->id()) {
        abort(403);
    }
    $venta->load(['cliente', 'pagos']);

    $pagosRealizados = $venta->pagos->count();
    $numeroPago      = $pagosRealizados + 1;
$pagosRestantes = max(1, $venta->num_pagos - $pagosRealizados);
$cuotaSugerida  = round($venta->saldo_pendiente / $pagosRestantes, 2);

return view('pagos.create', compact('venta', 'numeroPago', 'pagosRestantes', 'cuotaSugerida'));
}

    public function store(Request $request, Venta $venta)
    {
        if (auth()->user()->isCobrador() && $venta->cobrador_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'fecha_pago'   => 'required|date',
            'monto'        => 'required|numeric|min:0.01',
            'mora'         => 'nullable|numeric|min:0',
            'metodo_pago'  => 'required|in:efectivo,transferencia,tarjeta,otro',
            'notas'        => 'nullable|string',
        ]);

        if ($data['monto'] > $venta->saldo_pendiente) {
            return back()->withErrors(['monto' => 'El monto supera el saldo pendiente de $' . number_format($venta->saldo_pendiente, 2)]);
        }

        DB::transaction(function () use ($data, $venta) {
            $mora          = $data['mora'] ?? 0;
            $totalCobrado  = $data['monto'] + $mora;

            Pago::create([
                'numero_recibo' => 'R-' . str_pad(Pago::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'venta_id'      => $venta->id,
                'cobrador_id'   => auth()->id(),
                'fecha_pago'    => $data['fecha_pago'],
                'monto'         => $data['monto'],
                'mora'          => $mora,
                'total_cobrado' => $totalCobrado,
                'metodo_pago'   => $data['metodo_pago'],
                'notas'         => $data['notas'] ?? null,
            ]);

            $nuevoSaldo = max(0, $venta->saldo_pendiente - $data['monto']);
            $venta->update([
                'saldo_pendiente' => $nuevoSaldo,
                'estado'          => $nuevoSaldo <= 0 ? 'liquidada' : 'activa',
            ]);
        });

        return redirect()->route('ventas.show', $venta)->with('success', 'Pago registrado exitosamente.');
    }

    public function show(Pago $pago)
    {
        if (auth()->user()->isCobrador() && $pago->cobrador_id !== auth()->id()) {
            abort(403);
        }
        $pago->load(['venta.cliente', 'cobrador']);
        return view('pagos.show', compact('pago'));
    }

    public function destroy(Pago $pago)
    {
        if (auth()->user()->isCobrador()) {
            abort(403, 'Solo administradores pueden eliminar pagos.');
        }

        DB::transaction(function () use ($pago) {
            $venta = $pago->venta;
            $venta->update([
                'saldo_pendiente' => $venta->saldo_pendiente + $pago->monto,
                'estado'          => 'activa',
            ]);
            $pago->delete();
        });

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado y saldo restaurado.');
    }
}
