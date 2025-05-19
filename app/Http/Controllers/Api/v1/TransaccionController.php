<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class TransaccionController extends Controller
{
    public function index()
    {
        try {
            $transacciones = Transaccion::all();
            return response()->json([
                "message" => "Listado de transacciones",
                "status" => 200,
                "data" => $transacciones
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener las transacciones",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cartera_id' => 'required|integer|exists:carteras_electronicas,id',
                'monto' => 'required|numeric',
                'tipo_transaccion' => 'required|in:carga,retiro,pago',
                'descripcion' => 'nullable|string|max:255',
                'estado' => 'nullable|in:pendiente,completada,fallida',
            ]);

            $transaccion = Transaccion::create([
                'cartera_id' => $validatedData['cartera_id'],
                'monto' => $validatedData['monto'],
                'tipo_transaccion' => $validatedData['tipo_transaccion'],
                'descripcion' => $validatedData['descripcion'] ?? null,
                'estado' => $validatedData['estado'] ?? 'pendiente',
                'fecha_transaccion' => now(),
            ]);

            return response()->json([
                "message" => "Transacción creada correctamente",
                "status" => 201,
                "data" => $transaccion
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear la transacción",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $transaccion = Transaccion::findOrFail($id);
            return response()->json([
                "message" => "Información de la transacción",
                "status" => 200,
                "data" => $transaccion
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Transacción no encontrada",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transaccion = Transaccion::findOrFail($id);

            $validatedData = $request->validate([
                'cartera_id' => 'sometimes|required|integer|exists:carteras_electronicas,id',
                'monto' => 'sometimes|required|numeric',
                'tipo_transaccion' => 'sometimes|required|in:carga,retiro,pago',
                'descripcion' => 'nullable|string|max:255',
                'estado' => 'sometimes|required|in:pendiente,completada,fallida',
            ]);

            $transaccion->update($validatedData);

            return response()->json([
                "message" => "Transacción actualizada correctamente",
                "status" => 200,
                "data" => $transaccion
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Transacción no encontrada",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar la transacción",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $transaccion = Transaccion::findOrFail($id);
            $transaccion->delete();

            return response()->json([
                "message" => "Transacción eliminada correctamente",
                "status" => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Transacción no encontrada",
                "status" => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al eliminar la transacción",
                "status" => 500
            ], 500);
        }
    }
}
