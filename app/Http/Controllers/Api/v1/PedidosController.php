<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class PedidosController extends Controller
{
    public function index()
    {
        try {
            // Obtener pedidos solo del cliente autenticado
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
            $pedidos = Pedido::where('id_cliente', $cliente->id_cliente)->get();
            return response()->json([
                "message" => "Listado de pedidos del cliente autenticado",
                "status" => 200,
                "data" => $pedidos
            ], 200);
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

            // Validar datos mínimos para crear pedido
            $validatedData = $request->validate([
                'fecha_pedido' => 'required|date',
                'total' => 'required|numeric|min:0',
            ]);

            // Crear pedido con estado activo y cliente autenticado
            $pedido = Pedido::create([
                'id_cliente' => $cliente->id_cliente,
                'fecha_pedido' => $validatedData['fecha_pedido'],
                'total' => $validatedData['total'],
                'estado' => 'activo',
            ]);

            return response()->json([
                "message" => "Pedido (carrito) creado correctamente",
                "status" => 201,
                "data" => $pedido
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el pedido",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
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

            $pedido = Pedido::where('id', $id)
                            ->where('id_cliente', $cliente->id_cliente)
                            ->first();

            if (!$pedido) {
                return response()->json([
                    "message" => "Pedido no encontrado o no pertenece al cliente",
                    "status" => 404
                ], 404);
            }

            return response()->json([
                "message" => "Información del pedido",
                "status" => 200,
                "data" => $pedido
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Pedido no encontrado",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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

            $pedido = Pedido::where('id', $id)
                            ->where('id_cliente', $cliente->id_cliente)
                            ->first();

            if (!$pedido) {
                return response()->json([
                    "message" => "Pedido no encontrado o no pertenece al cliente",
                    "status" => 404
                ], 404);
            }

            $validatedData = $request->validate([
                'fecha_pedido' => 'required|date',
                'total' => 'required|numeric|min:0',
                'estado' => 'required|string'
            ]);

            $pedido->update($validatedData);

            return response()->json([
                "message" => "Pedido actualizado correctamente",
                "status" => 200,
                "data" => $pedido
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Pedido no encontrado",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar el pedido",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
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

            $pedido = Pedido::where('id', $id)
                            ->where('id_cliente', $cliente->id_cliente)
                            ->first();

            if (!$pedido) {
                return response()->json([
                    "message" => "Pedido no encontrado o no pertenece al cliente",
                    "status" => 404
                ], 404);
            }

            $pedido->delete();

            return response()->json([
                "message" => "Pedido eliminado correctamente",
                "status" => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Pedido no encontrado",
                "status" => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al eliminar el pedido",
                "status" => 500
            ], 500);
        }
    }
}
