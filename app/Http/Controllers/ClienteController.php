<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    private function query()
    {
        $user = auth()->user();
        $q = Cliente::with('cobrador');
        if ($user->isCobrador()) {
            $q->where('cobrador_id', $user->id);
        }
        return $q;
    }

    public function index(Request $request)
    {
        $search   = $request->get('search');
        $clientes = $this->query()
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%")
                  ->orWhere('dui', 'like', "%{$search}%");
            }))
            ->orderBy('nombre')
            ->paginate(20);

        return view('clientes.index', compact('clientes', 'search'));
    }

    public function create()
    {
        $cobradores = auth()->user()->isAdmin()
            ? User::whereHas('role', fn($q) => $q->where('nombre', 'cobrador'))->where('activo', true)->get()
            : collect([auth()->user()]);

        return view('clientes.create', compact('cobradores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'     => 'required|string|max:100',
            'apellido'   => 'required|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:150',
            'direccion'  => 'nullable|string|max:255',
            'dui'        => 'nullable|string|max:20',
            'nit'        => 'nullable|string|max:20',
            'referencias'=> 'nullable|string',
            'cobrador_id'=> 'required|exists:users,id',
        ]);

        // Cobradores can only create clients for themselves
        if (auth()->user()->isCobrador()) {
            $data['cobrador_id'] = auth()->id();
        }

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $this->authorizeCliente($cliente);
        $cliente->load(['cobrador', 'ventas.pagos', 'ventas.detalles.producto']);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $this->authorizeCliente($cliente);

        $cobradores = auth()->user()->isAdmin()
            ? User::whereHas('role', fn($q) => $q->where('nombre', 'cobrador'))->where('activo', true)->get()
            : collect([auth()->user()]);

        return view('clientes.edit', compact('cliente', 'cobradores'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->authorizeCliente($cliente);

        $data = $request->validate([
            'nombre'     => 'required|string|max:100',
            'apellido'   => 'required|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:150',
            'direccion'  => 'nullable|string|max:255',
            'dui'        => 'nullable|string|max:20',
            'nit'        => 'nullable|string|max:20',
            'referencias'=> 'nullable|string',
            'cobrador_id'=> 'required|exists:users,id',
            'activo'     => 'boolean',
        ]);

        if (auth()->user()->isCobrador()) {
            $data['cobrador_id'] = auth()->id();
        }

        $cliente->update($data);

        return redirect()->route('clientes.show', $cliente)->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorizeCliente($cliente);
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    private function authorizeCliente(Cliente $cliente): void
    {
        if (auth()->user()->isCobrador() && $cliente->cobrador_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a este cliente.');
        }
    }
}
