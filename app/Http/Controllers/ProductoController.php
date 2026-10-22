<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search');
        $categoria  = $request->get('categoria_id');
        $bajo_stock = $request->boolean('bajo_stock');

        $productos = Producto::with('categoria')
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('codigo', 'like', "%{$search}%"))
            ->when($categoria, fn($q) => $q->where('categoria_id', $categoria))
            ->when($bajo_stock, fn($q) => $q->whereColumn('stock', '<=', 'stock_minimo'))
            ->orderBy('nombre')
            ->paginate(20);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias', 'search', 'categoria', 'bajo_stock'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo'        => 'required|string|max:50|unique:productos',
            'nombre'        => 'required|string|max:200',
            'descripcion'   => 'nullable|string',
            'categoria_id'  => 'nullable|exists:categorias,id',
            'precio_costo'  => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
            'imagen'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load('categoria', 'ventaDetalles.venta.cliente');
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'codigo'        => 'required|string|max:50|unique:productos,codigo,' . $producto->id,
            'nombre'        => 'required|string|max:200',
            'descripcion'   => 'nullable|string',
            'categoria_id'  => 'nullable|exists:categorias,id',
            'precio_costo'  => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
            'activo'        => 'boolean',
            'imagen'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('productos.show', $producto)->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}
