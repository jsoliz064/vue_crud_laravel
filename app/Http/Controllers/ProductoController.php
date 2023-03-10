<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;

use Illuminate\Support\Facades\Cache;

class ProductoController extends Controller
{
    public function index(){
        $venta=new Venta();
        $venta->Cache();
        
        $query1=["id",">","9000"];
        $query2=["total",">","999"];
        
        $querys=[];
        $querys[]=$query1;
        $querys[]=$query2;
        return $venta->WhereCache($querys);
        
        $productos = Producto::where('precio', '>', 99)
        ->orderBy('nombre')
        ->cache();
        
        return $productos;
    }
    public function buscarProducto($id)
    {
        $resultado = null;
        $totalProductos = Cache::remember("total_productos", 60 * 60, function () {
            return Producto::count();
        });
        $numVariablesCache = ceil($totalProductos / 10000);
        for ($i = 1; $i <= $numVariablesCache; $i++) {
            $productos = Cache::get('productos_' . $i);
            $prod = $productos->where('id', $id)->first();
            if ($prod) {
                return $prod;
            }
        }
        return $resultado;
    }
    public function buscarProducto2($id)
    {
        $paquetes = $this->productos_keys();
        foreach ($paquetes as $paquete) {
            $key = $this->productos_key($paquete);
            if ($id >= $key['inicio'] && $id <= $key['fin']) {
                $productos = Cache::get($paquete);
                $prod = $productos->where('id', $id)->first();
                if ($prod) {
                    return $prod;
                }
            }
        }
        return [];
    }
    public function editarProducto(Request $request, $id)
    {
        $paquetes = $this->productos_keys();
        foreach ($paquetes as $paquete) {
            $key = $this->productos_key($paquete);
            if ($id >= $key['inicio'] && $id <= $key['fin']) {
                $productos = Cache::get($paquete);
                $prod = $productos->where('id', $id)->first();
                if ($prod) {
                    $prod->update($request->all());
                    Cache::put($paquete, $productos, 60 * 60);
                    return $prod;
                }
            }
        }

        return [];
    }
    public function eliminarProducto($id)
    {
        $paquetes = $this->productos_keys();
        foreach ($paquetes as $paquete) {
            $key = $this->productos_key($paquete);
            if ($id >= $key['inicio'] && $id <= $key['fin']) {
                $productos = Cache::get($paquete);
                $indice = $productos->search(function ($item, $key) use ($id) {
                    return $item->id == $id;
                });
                if ($indice !== false) {
                    $productos->forget($indice);
                    Cache::put($paquete, $productos, 60 * 60);
                    return "eliminado";
                }
            }
        }
        return "no encontrado";
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
