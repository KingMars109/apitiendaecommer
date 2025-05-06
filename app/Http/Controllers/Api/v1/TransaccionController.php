<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TransaccionController extends Controller
{
    public function index()
    {
        return response()->json(Transaccion::all(), 200);
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
                "transaccion" => $transaccion
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $transaccion = Transaccion::findOrFail($id);
            return response()->json($transaccion, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Transacción no encontrada", "status" => 404], 404);
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
                "transaccion" => $transaccion
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Transacción no encontrada", "status" => 404], 404);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $transaccion = Transaccion::findOrFail($id);
            $transaccion->delete();

            return response()->json(["message" => "Transacción eliminada correctamente"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Transacción no encontrada", "status" => 404], 404);
        }
    }
}
