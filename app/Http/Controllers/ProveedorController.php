<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $proveedores = Proveedor::when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('contacto', 'like', "%{$search}%")
                ->orWhere('telefono', 'like', "%{$search}%"))
            ->orderBy('nombre')
            ->paginate(20);

        return view('proveedores.index', compact('proveedores', 'search'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'contacto'  => 'nullable|string|max:150',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'notas'     => 'nullable|string',
        ]);

        Proveedor::create($data);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'contacto'  => 'nullable|string|max:150',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'notas'     => 'nullable|string',
            'activo'    => 'boolean',
        ]);

        $proveedor->update($data);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}
