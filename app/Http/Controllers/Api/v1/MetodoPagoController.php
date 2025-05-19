<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class MetodoPagoController extends Controller
{
    public function index()
    {
        try {
            $metodosPago = MetodoPago::all();
            return response()->json([
                "message" => "Listado de métodos de pago",
                "status" => 200,
                "data" => $metodosPago
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los métodos de pago",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cartera_id' => 'required|integer|exists:carteras_electronicas,id',
                'tipo_pago' => 'required|in:tarjeta_credito,tarjeta_debito,transferencia,otro',
                'informacion_pago' => 'required|string|max:255',
                'fecha_expiracion' => 'nullable|date',
            ]);

            $metodoPago = MetodoPago::create($validatedData);

            return response()->json([
                "message" => "Método de pago creado correctamente",
                "status" => 201,
                "data" => $metodoPago
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el método de pago",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);
            return response()->json([
                "message" => "Información del método de pago",
                "status" => 200,
                "data" => $metodoPago
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Método de pago no encontrado",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);

            $validatedData = $request->validate([
                'cartera_id' => 'sometimes|required|integer|exists:carteras_electronicas,id',
                'tipo_pago' => 'sometimes|required|in:tarjeta_credito,tarjeta_debito,transferencia,otro',
                'informacion_pago' => 'sometimes|required|string|max:255',
                'fecha_expiracion' => 'nullable|date',
            ]);

            $metodoPago->update($validatedData);

            return response()->json([
                "message" => "Método de pago actualizado correctamente",
                "status" => 200,
                "data" => $metodoPago
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Método de pago no encontrado",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar el método de pago",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);
            $metodoPago->delete();

            return response()->json([
                "message" => "Método de pago eliminado correctamente",
                "status" => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Método de pago no encontrado",
                "status" => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al eliminar el método de pago",
                "status" => 500
            ], 500);
        }
    }
}
