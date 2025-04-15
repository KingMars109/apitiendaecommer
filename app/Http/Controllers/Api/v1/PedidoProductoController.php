<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class PedidoProductoController extends Controller
{
    public function index()
    {
        try {
            $pedidoProductos = PedidoProducto::all();
            return response()->json([
                "message" => "Listado de pedidos-productos",
                "status" => 200,
                "data" => $pedidoProductos
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los datos",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_pedido' => 'required|integer|exists:pedidos,id',
                'id_producto' => 'required|integer|exists:productos,id',
                'cantidad' => 'required|integer|min:1',
            ]);

            $pedidoProducto = PedidoProducto::create($validatedData);

            return response()->json([
                "message" => "PedidoProducto creado correctamente",
                "status" => 201,
                "data" => $pedidoProducto
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el PedidoProducto",
                "status" => 500
            ], 500);
        }
    }

    public function show($id_pedido, $id_producto)
    {
        try {
            $pedidoProducto = PedidoProducto::where('id_pedido', $id_pedido)
                                            ->where('id_producto', $id_producto)
                                            ->firstOrFail();
            return response()->json([
                "message" => "Información del PedidoProducto",
                "status" => 200,
                "data" => $pedidoProducto
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "PedidoProducto no encontrado",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id_pedido, $id_producto)
    {
        try {
            $pedidoProducto = PedidoProducto::where('id_pedido', $id_pedido)
                                            ->where('id_producto', $id_producto)
                                            ->firstOrFail();
            
            $validatedData = $request->validate([
                'cantidad' => 'required|integer|min:1',
            ]);
            
            $pedidoProducto->update($validatedData);
            
            return response()->json([
                "message" => "PedidoProducto actualizado correctamente",
                "status" => 200,
                "data" => $pedidoProducto
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "PedidoProducto no encontrado",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar el PedidoProducto",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id_pedido, $id_producto)
    {
        try {
            $pedidoProducto = PedidoProducto::where('id_pedido', $id_pedido)
                                            ->where('id_producto', $id_producto)
                                            ->firstOrFail();
            
            $pedidoProducto->delete();
            
            return response()->json([
                "message" => "PedidoProducto eliminado correctamente",
                "status" => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "PedidoProducto no encontrado",
                "status" => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al eliminar el PedidoProducto",
                "status" => 500
            ], 500);
        }
    }
}
