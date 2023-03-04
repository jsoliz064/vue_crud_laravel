<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Cache;
class ProductoController extends Controller
{
    public function index()
    {
        $productos=Producto::All();
        // $productos = cache()->remember('productos', 60*60*24, function () {
        //     return Producto::All();
        // });
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

        // if (Cache::has('productos')) {
        //     $productos = Cache::get('productos');
        //     $producto_cache=$productos->find($producto->id);
        //     $producto_cache->nombre=$producto->nombre;
        //     $producto_cache->costo=$producto->costo;
        //     $producto_cache->precio=$producto->precio;
        //     Cache::put('productos', $productos, 2);
        // }

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
