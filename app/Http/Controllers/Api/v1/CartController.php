<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CartController extends Controller
{
    /**
     * Obtener el carrito activo del usuario autenticado
     */
    public function getCart()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                "message" => "No autenticado",
                "status" => 401
            ], 401);
        }

        $cliente = $user->cliente;
        if (!$cliente) {
            return response()->json([
                "message" => "Cliente no encontrado para el usuario",
                "status" => 404
            ], 404);
        }

        $pedido = Pedido::where('id_cliente', $cliente->id_cliente)
                        ->where('estado', 'activo')
                        ->with('pedidoProductos.producto')
                        ->first();

        if (!$pedido) {
            return response()->json([
                "message" => "Carrito vacío",
                "status" => 200,
                "data" => []
            ], 200);
        }

        $cartItems = $pedido->pedidoProductos->map(function ($item) {
            return [
                'product_id' => $item->id_producto,
                'quantity' => $item->cantidad,
                'product' => $item->producto,
            ];
        });

        return response()->json([
            "message" => "Carrito cargado",
            "status" => 200,
            "data" => $cartItems
        ], 200);
    }

    /**
     * Guardar o actualizar el carrito del usuario autenticado
     * Espera un array de items: [{ product_id: number, quantity: number }]
     */
    public function saveCart(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                "message" => "No autenticado",
                "status" => 401
            ], 401);
        }

        $cliente = $user->cliente;
        if (!$cliente) {
            return response()->json([
                "message" => "Cliente no encontrado para el usuario",
                "status" => 404
            ], 404);
        }

        $items = $request->input('items');
        if (!is_array($items)) {
            return response()->json([
                "message" => "Formato inválido para items",
                "status" => 422
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Buscar o crear pedido activo
            $pedido = Pedido::firstOrCreate(
                ['id_cliente' => $cliente->id_cliente, 'estado' => 'activo'],
                ['fecha_pedido' => now(), 'total' => 0]
            );

            // Eliminar productos actuales del pedido
            $pedido->pedidoProductos()->delete();

            $total = 0;

            // Agregar productos nuevos
            foreach ($items as $item) {
                $pedidoProducto = new PedidoProducto();
                $pedidoProducto->id_pedido = $pedido->id;
                $pedidoProducto->id_producto = $item['product_id'];
                $pedidoProducto->cantidad = $item['quantity'];
                $pedidoProducto->save();

                // Calcular total (asumiendo que producto tiene precio)
                $producto = $pedidoProducto->producto;
                if ($producto) {
                    $total += $producto->precio * $item['quantity'];
                }
            }

            // Actualizar total del pedido
            $pedido->total = $total;
            $pedido->save();

            DB::commit();

            return response()->json([
                "message" => "Carrito guardado correctamente",
                "status" => 200,
                "data" => $pedido
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Error al guardar el carrito",
                "status" => 500,
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
