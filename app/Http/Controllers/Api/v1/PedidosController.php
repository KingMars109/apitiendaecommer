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
            $pedidos = Pedido::all();
            return response()->json([
                "message" => "Listado de pedidos",
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
            $validatedData = $request->validate([
                'id_cliente' => 'required|exists:clientes,id',
                'fecha_pedido' => 'required|date',
                'total' => 'required|numeric|min:0',
            ]);

            $pedido = Pedido::create($validatedData);

            return response()->json([
                "message" => "Pedido creado correctamente",
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
            $pedido = Pedido::findOrFail($id);
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
            $pedido = Pedido::findOrFail($id);

            $validatedData = $request->validate([
                'id_cliente' => 'required|exists:clientes,id',
                'fecha_pedido' => 'required|date',
                'total' => 'required|numeric|min:0',
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
            $pedido = Pedido::findOrFail($id);
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
