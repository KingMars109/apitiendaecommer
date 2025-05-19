<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CarteraElectronica;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class CarteraElectronicaController extends Controller
{
    public function index()
    {
        try {
            $carteras = CarteraElectronica::all();
            return response()->json([
                "message" => "Listado de carteras electrónicas",
                "status" => 200,
                "data" => $carteras
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener las carteras electrónicas",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:usuarios,id',
                'saldo' => 'nullable|numeric|min:0',
                'estado' => 'nullable|in:activa,suspendida',
            ]);

            $cartera = CarteraElectronica::create([
                'user_id' => $validatedData['user_id'],
                'saldo' => $validatedData['saldo'] ?? 0.00,
                'estado' => $validatedData['estado'] ?? 'activa',
                'fecha_creacion' => now(),
            ]);

            return response()->json([
                "message" => "Cartera electrónica creada correctamente",
                "status" => 201,
                "data" => $cartera
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear la cartera electrónica",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $cartera = CarteraElectronica::findOrFail($id);
            return response()->json([
                "message" => "Información de la cartera electrónica",
                "status" => 200,
                "data" => $cartera
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Cartera electrónica no encontrada",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cartera = CarteraElectronica::findOrFail($id);

            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|integer|exists:usuarios,id',
                'saldo' => 'sometimes|required|numeric|min:0',
                'estado' => 'sometimes|required|in:activa,suspendida',
            ]);

            $cartera->update($validatedData);

            return response()->json([
                "message" => "Cartera electrónica actualizada correctamente",
                "status" => 200,
                "data" => $cartera
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Cartera electrónica no encontrada",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar la cartera electrónica",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cartera = CarteraElectronica::findOrFail($id);
            $cartera->delete();

            return response()->json([
                "message" => "Cartera electrónica eliminada correctamente",
                "status" => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Cartera electrónica no encontrada",
                "status" => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al eliminar la cartera electrónica",
                "status" => 500
            ], 500);
        }
    }
}
