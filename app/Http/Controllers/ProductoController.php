<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos=Producto::All();
        return $productos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'costo' => $request->costo,
            'precio' => $request->precio,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto->update([
            'nombre' => $request->nombre,
            'costo' => $request->costo,
            'precio' => $request->precio,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
    }
}
