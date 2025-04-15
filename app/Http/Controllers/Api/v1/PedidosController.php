<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PedidosController extends Controller
{
    public function index()
    {
        return response()->json(Pedido::all(), 200);
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
                "pedido" => $pedido
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            return response()->json(Pedido::findOrFail($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Pedido no encontrado", "status" => 404], 404);
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
                "pedido" => $pedido
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Pedido no encontrado", "status" => 404], 404);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->delete();

            return response()->json(["message" => "Pedido eliminado correctamente"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Pedido no encontrado", "status" => 404], 404);
        }
    }
}
